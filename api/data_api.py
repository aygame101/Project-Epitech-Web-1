from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
import logging

from sqlalchemy import func
from sqlalchemy import inspect
#pour hacher les mdp
from werkzeug.security import generate_password_hash
from werkzeug.security import check_password_hash
import sqlite3

from flask_cors import CORS
app = Flask(__name__)
CORS(app)

app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://root:@localhost/web_project"  #nom + info de connexion de la database
db = SQLAlchemy(app)


logging.basicConfig(level=logging.DEBUG)

# definire les models
class People(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    is_applier = db.Column(db.Integer, nullable=False)
    mail = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(100))
    firstname = db.Column(db.String(100))
    phone = db.Column(db.String(20))
    password = db.Column(db.String(255))

class Companies(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False, unique=True)
    password = db.Column(db.String(255), nullable=False)

class JobAds(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    company_name = db.Column(db.String(100))
    city = db.Column(db.String(255), nullable=False)
    job_title = db.Column(db.String(255), nullable=False)
    contract_type = db.Column(db.String(100), nullable=False)
    wage = db.Column(db.String(30), nullable=False)
    mail_in_charge = db.Column(db.String(30), nullable=False)
    description_job = db.Column(db.Text, nullable=False)

class RequestStorage(db.Model):
    __tablename__ = 'requeststorage'
    id = db.Column(db.Integer, primary_key=True)
    id_annonce_postule = db.Column(db.Integer, db.ForeignKey('job_ads.id'))
    applayer_info = db.Column(db.Integer, db.ForeignKey('people.id'))
    id_company = db.Column(db.Integer, db.ForeignKey('companies.id'))
    
class Admin(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(255), unique=True, nullable=False)
    password = db.Column(db.String(255), nullable=False)

# routes de bases
@app.route("/people", methods=["GET"])
def get_people():
    people = People.query.all()
    output = [{"id": person.id, "name": person.name, "firstname": person.firstname, "mail": person.mail, "password": person.password} for person in people]
    return jsonify({"people": output})

@app.route('/people', methods=['POST'])
def add_person():
    data = request.json
    
    required_fields = ['name', 'mail', 'password', 'is_applier']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400

    hashed_password = generate_password_hash(data['password'])

    try:
        new_person = People(
            name=data['name'],
            mail=data['mail'],
            password=hashed_password,
            is_applier=data['is_applier'],
            firstname=data.get('firstname'),
            phone=data.get('phone')
        )
        db.session.add(new_person)
        db.session.commit()
        return jsonify({
            "message": "Person added successfully", 
            "id": new_person.id,
            "name": new_person.name,
            "mail": new_person.mail,
            "is_applier": new_person.is_applier,
            "firstname": new_person.firstname,
            "phone": new_person.phone
        }), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 500

@app.route("/people/<id>", methods=["GET"])
def get_person(id):
    person = People.query.get(id)
    if person is None:
        return jsonify({"message": "Person not found"}), 404
    return jsonify({
        "id": person.id, 
        "name": person.name, 
        "firstname": person.firstname, 
        "mail": person.mail,
        "phone":  person.phone,
        "password": ""
    })

@app.route("/people/<id>", methods=["PUT"])
def update_person(id):
    person = People.query.get(id)
    if person is None:
        return jsonify({"message": "Person not found"}), 404
    data = request.get_json()
    person.name = data.get("name", person.name)
    person.firstname = data.get("firstname", person.firstname)
    person.mail = data.get("mail", person.mail)
    if "password" in data:
        hashed_password = generate_password_hash(data["password"])
        person.password = hashed_password
    
    db.session.commit()
    return jsonify({"message": "Person updated successfully"})

@app.route("/people/<id>", methods=["DELETE"])
def delete_person(id):
    person = People.query.get(id)
    if person is None:
        return jsonify({"message": "Person not found"}), 404
    db.session.delete(person)
    db.session.commit()
    return jsonify({"message": "Person deleted successfully"})

@app.route("/companies", methods=["GET"])
def get_companies():
    companies = Companies.query.all()
    output = [{"id": company.id, "name": company.name, "password": company.password} for company in companies]
    return jsonify({"companies": output})

@app.route("/companies", methods=["POST"])
def create_company():
    data = request.get_json()
    hashed_password = generate_password_hash(data["password"])
    new_company = Companies(
        name=data["name"],
        password=hashed_password
    )
    db.session.add(new_company)
    db.session.commit()
    return jsonify({"message": "Company created successfully"})

@app.route("/companies/<id>", methods=["GET"])
def get_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    return jsonify({
        "id": company.id, 
        "name": company.name,
        "password": ""
    })
    
@app.route("/companies/<id>", methods=["PUT"])
def update_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    data = request.get_json()
    company.name = data.get("name", company.name)
    if "password" in data:
        hashed_password = generate_password_hash(data["password"])
        company.password = hashed_password
    
    db.session.commit()
    return jsonify({"message": "Company updated successfully"})

@app.route("/companies/<id>", methods=["DELETE"])
def delete_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    db.session.delete(company)
    db.session.commit()
    return jsonify({"message": "Company deleted successfully"})

@app.route("/job_ads", methods=["GET"])
def get_job_ads():
    job_ads = JobAds.query.all()
    output = [{"id": ad.id, "company_name": ad.company_name, "job_title": ad.job_title, "city": ad.city, "wage": ad.wage, "contract_type": ad.contract_type, "description": ad.description_job} for ad in job_ads]
    return jsonify({"job_ads": output})

@app.route("/job_ads", methods=["POST"])
def create_job_ads():
    data = request.get_json()
    new_ad = JobAds(
        company_name=data["company_name"],
        city=data["city"],
        job_title=data["job_title"],
        contract_type=data["contract_type"],
        wage=data["wage"],
        mail_in_charge=data["mail_in_charge"],
        description_job=data["description_job"]
    )
    db.session.add(new_ad)
    db.session.commit()
    return jsonify({"message": "job_ads created successfully"})

@app.route("/job_ads/<int:id>", methods=["GET"])
def get_job_ads_by_id(id):
    ad = JobAds.query.get(id)
    if ad is None:
        return jsonify({"message": "job_ads not found"}), 404
    return jsonify({
        "id": ad.id,
        "company_name": ad.company_name,
        "job_title": ad.job_title,
        "city": ad.city,
        "wage": ad.wage,
        "contract_type": ad.contract_type,
        "description_job": ad.description_job,
        "mail_in_charge": ad.mail_in_charge
    })

@app.route("/job_ads/<int:id>", methods=["PUT"])
def update_job_ads(id):
    ad = JobAds.query.get(id)
    if ad is None:
        return jsonify({"message": "job_ads not found"}), 404
    data = request.get_json()
    for key, value in data.items():
        setattr(ad, key, value)
    db.session.commit()
    return jsonify({"message": "job_ads updated successfully"})

@app.route("/job_ads/<id>", methods=["DELETE"])
def delete_job_ads(id):
    ad = JobAds.query.get(id)
    if ad is None:
        return jsonify({"message": "job_ads not found"}), 404
    db.session.delete(ad)
    db.session.commit()
    return jsonify({"message": "job_ads deleted successfully"})

@app.route("/admin", methods=["GET"])
def get_admins():
    admins = Admin.query.all()
    output = [{"id": admin.id, "username": admin.username} for admin in admins]
    return jsonify({"admins": output})

@app.route("/admin", methods=["POST"])
def create_admin():
    data = request.get_json()
    hashed_password = generate_password_hash(data["password"])
    new_admin = Admin(
        username=data["username"],
        password=hashed_password
    )
    db.session.add(new_admin)
    db.session.commit()
    return jsonify({"message": "Admin created successfully"}), 201

@app.route("/admin/<id>", methods=["GET"])
def get_admin(id):
    admin = Admin.query.get(id)
    if admin is None:
        return jsonify({"message": "Admin not found"}), 404
    return jsonify({
        "id": admin.id, 
        "username": admin.username
    })
    
@app.route("/admin/<id>", methods=["PUT"])
def update_admin(id):
    admin = Admin.query.get(id)
    if admin is None:
        return jsonify({"message": "Admin not found"}), 404
    data = request.get_json()
    admin.username = data.get("username", admin.username)
    if "password" in data:
        hashed_password = generate_password_hash(data["password"])
        admin.password = hashed_password
    
    db.session.commit()
    return jsonify({"message": "Admin updated successfully"})

@app.route("/admin/<id>", methods=["DELETE"])
def delete_admin(id):
    admin = Admin.query.get(id)
    if admin is None:
        return jsonify({"message": "Admin not found"}), 404
    db.session.delete(admin)
    db.session.commit()
    return jsonify({"message": "Admin deleted successfully"})

@app.route("/admin/login", methods=["POST"])
def login_admin():
    data = request.json
    username = data.get('username')
    password = data.get('password')

    admin = Admin.query.filter_by(username=username).first()

    if admin and check_password_hash(admin.password, password):
        return jsonify({"id": admin.id, "message": "Login successful"}), 200
    else:
        return jsonify({"message": "Invalid username or password"}), 401

    #routes pour l'index.php
    
@app.route("/job_titles", methods=["GET"])
def get_job_titles():
    job_titles = db.session.query(JobAds.job_title).distinct().all()
    return jsonify({"job_titles": [title[0] for title in job_titles]})

@app.route("/contract_types", methods=["GET"])
def get_contract_types():
    contract_types = db.session.query(JobAds.contract_type).distinct().all()
    return jsonify({"contract_types": [type[0] for type in contract_types]})
# le script de get_location permet de n'afficher que les noms de villes
@app.route("/locations", methods=["GET"])
def get_locations():
    locations = db.session.query(
        func.regexp_replace(
            func.regexp_replace(JobAds.city, r',.*$', ''),  
            r'\s+\d.*$', '' 
        ).label('city')
    ).distinct().order_by('city').all()
    clean_locations = list(set(location.city.strip().capitalize() for location in locations if location.city))
    return jsonify({"locations": clean_locations})

    #recherche les offre celon les critere rentrer dans index.php

@app.route("/search", methods=["GET"])
def search_jobs():
    job_title = request.args.get('job_title')
    contract_type = request.args.get('contract_type')
    location = request.args.get('location')

    query = JobAds.query

    if job_title:
        query = query.filter(JobAds.job_title.like(f"%{job_title}%"))
    if contract_type:
        query = query.filter(JobAds.contract_type.like(f"%{contract_type}%"))
    if location:
        query = query.filter(JobAds.city.like(f"%{location}%"))

    results = query.all()
    output = [{"id": ad.id, "company_name": ad.company_name, "job_title": ad.job_title, "city": ad.city, "wage": ad.wage, "contract_type": ad.contract_type, "description": ad.description_job} for ad in results]
    return jsonify({"job_ads": output})

    #route pour menu de la page admin
    
@app.route("/table_names", methods=["GET"])
def get_table_names():
    inspector = inspect(db.engine)
    table_names = inspector.get_table_names()
    filtered_table_names = [name for name in table_names if name.lower() != 'admin']
    return jsonify({"table_names": filtered_table_names})

    #pour la page login
    
@app.route("/people/login", methods=["POST"])
def login_people():
    data = request.json
    email = data.get('email')
    password = data.get('password')

    user = People.query.filter_by(mail=email).first()

    if user and check_password_hash(user.password, password):
        return jsonify({"id": user.id, "message": "Login successful"}), 200
    else:
        return jsonify({"message": "Invalid email or password"}), 401

@app.route("/companies/login", methods=["POST"])
def login_companies():
    data = request.json
    name = data.get('email') 
    password = data.get('password')

    company = Companies.query.filter_by(name=name).first()

    if company and check_password_hash(company.password, password):
        return jsonify({"id": company.id, "message": "Login successful"}), 200
    else:
        return jsonify({"message": "Invalid company name or password"}), 401

    #route pour lorsque l'on postule
    
@app.route("/apply", methods=["POST"])
def apply_for_job():
    data = request.json
    required_fields = ['id_annonce_postule', 'applayer_info', 'id_company']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400

    try:
        new_request = RequestStorage(
            id_annonce_postule=data['id_annonce_postule'],
            applayer_info=data['applayer_info'],
            id_company=data['id_company']
        )
        db.session.add(new_request)
        db.session.commit()
        return jsonify({"message": "Application submitted successfully", "id": new_request.id}), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 500

    #recherche un entreprise par son nom
@app.route("/companies/search", methods=["GET"])
def search_company():
    name = request.args.get('name')
    if not name:
        return jsonify({"error": "Company name is required"}), 400
    
    company = Companies.query.filter_by(name=name).first()
    if company:
        return jsonify({"id": company.id, "name": company.name}), 200
    else:
        return jsonify({"error": "Company not found"}), 404
    
    #regarde a quoi un user a apply 
    
@app.route("/applied_jobs/<int:user_id>", methods=["GET"])
def get_applied_jobs(user_id):
    applied_jobs = db.session.query(JobAds)\
        .join(RequestStorage, JobAds.id == RequestStorage.id_annonce_postule)\
        .filter(RequestStorage.applayer_info == user_id)\
        .all()
    
    output = []
    for job in applied_jobs:
        job_data = {
            "id": job.id,
            "job_title": job.job_title,
            "company_name": job.company_name,
            "city": job.city,
            "contract_type": job.contract_type,
            "wage": job.wage,
            "description_job": job.description_job
        }
        output.append(job_data)
    
    return jsonify(output)

    #regarde si un mail existe deja dans la table people
    
@app.route("/people/search", methods=["GET"])
def search_people():
    email = request.args.get('email')
    if not email:
        return jsonify({"error": "Email parameter is required"}), 400
    
    person = People.query.filter_by(mail=email).first()
    if person:
        return jsonify({"id": person.id, "name": person.name, "email": person.mail})
    else:
        return jsonify({}), 200
    
    #regarde si un nom existe deja dans la table companie
    
@app.route("/companies/search", methods=["GET"])
def search_comp():
    name = request.args.get('name')
    if not name:
        return jsonify({"error": "Company name is required"}), 400
    
    company = Companies.query.filter_by(name=name).first()
    if company:
        return jsonify({"id": company.id, "name": company.name}), 200
    else:
        return jsonify({"error": "Company not found"}), 404

    #lance l'API
    
if __name__ == "__main__":
    app.run(debug=True, port=8000)