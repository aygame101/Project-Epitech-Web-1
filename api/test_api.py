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

app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://root:@localhost/test"  #nom + info de connexion de la database
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
    id = db.Column(db.Integer, primary_key=True)
    id_annonce_postule = db.Column(db.Integer, db.ForeignKey('job_ads.id'))
    applayer_info = db.Column(db.Integer, db.ForeignKey('people.id'))
    id_company = db.Column(db.Integer, db.ForeignKey('companies.id'))

# routes de bases
@app.route("/people", methods=["GET"])
def get_people():
    people = People.query.all()
    output = [{"id": person.id, "name": person.name, "firstname": person.firstname, "mail": person.mail, "password": person.password} for person in people]
    return jsonify({"people": output})

@app.route('/people', methods=['POST'])
def add_person():
    data = request.json
    
    required_fields = ['name', 'email', 'password', 'is_applier']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400

    hashed_password = generate_password_hash(data['password'])

    try:
        conn = sqlite3.connect('your_database.db')
        cursor = conn.cursor()
        cursor.execute('''
            INSERT INTO people (name, email, password, is_applier)
            VALUES (?, ?, ?, ?)
        ''', (data['name'], data['email'], hashed_password, data['is_applier']))
        conn.commit()
        new_id = cursor.lastrowid
        conn.close()
        return jsonify({"message": "Person added successfully", "id": new_id}), 201
    except sqlite3.Error as e:
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
    return jsonify({"table_names": table_names})

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


    #lance l'API
    
if __name__ == "__main__":
    app.run(debug=True, port=8000)