from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
import logging
from flask_cors import CORS
from sqlalchemy import func
#from flask_jwt_extended import JWTManager, jwt_required, create_access_token
from flask_login import LoginManager
from flask import request, jsonify
from flask_login import login_user
from flask_login import UserMixin
from flask_login import logout_user
from flask_login import login_required
from flask_sqlalchemy import SQLAlchemy
from flask_sqlalchemy  import User 
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://root:@localhost/test"
app.config["JWT_SECRET_KEY"] = "xb9xe8xf1xf9Qxe3/x86x96x93xd3xe1xee0xadxbbx8bz)lxfdx07xb4xca"  # Change this to a secure secret key
db = SQLAlchemy(app)
#jwt = JWTManager(app)
CORS(app)

# Set up logging
login_manager = LoginManager()
login_manager.init_app(app)


# Define class
class People(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    is_applier = db.Column(db.Integer, nullable=False)
    mail = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(100))
    firstname = db.Column(db.String(100))
    phone = db.Column(db.String(20))
    password = db.Column(db.String(255))
    salt = db.Column(db.String(255))

class Companies(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False, unique=True)
    password = db.Column(db.String(255), nullable=False)
    salt = db.Column(db.String(255), nullable=False)

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
    
"""class User(UserMixin, db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), unique=True, nullable=False)
    password = db.Column(db.String(128), nullable=False)

    def __init__(self, username, password):
        self.username = username
        self.password = password

    def __repr__(self):
        return f"User ('{self.username}')" """

# API routes
@app.route("/people", methods=["GET"])
def get_people():
    people = People.query.all()
    output = [{"id": person.id, "name": person.name, "firstname": person.firstname, "mail": person.mail} for person in people]
    return jsonify({"people": output})

@app.route("/people", methods=["POST"])
def create_person():
    data = request.get_json()
    new_person = People(name=data["name"], firstname=data["firstname"], mail=data["mail"], applying=data["applying"])
    db.session.add(new_person)
    db.session.commit()
    return jsonify({"message": "Person created successfully"})

@app.route("/people/<id>", methods=["GET"])
def get_person(id):
    person = People.query.get(id)
    if person is None:
        return jsonify({"message": "Person not found"}), 404
    return jsonify({"id": person.id, "name": person.name, "firstname": person.firstname, "mail": person.mail})

@app.route("/people/<id>", methods=["PUT"])
def update_person(id):
    person = People.query.get(id)
    if person is None:
        return jsonify({"message": "Person not found"}), 404
    data = request.get_json()
    person.name = data["name"]
    person.firstname = data["firstname"]
    person.mail = data["mail"]
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
    output = [{"id": company.id, "name": company.name} for company in companies]
    return jsonify({"companies": output})

@app.route("/companies", methods=["POST"])
def create_company():
    data = request.get_json()
    new_company = Companies(name=data["name"], password=data["password"], salt=data["salt"])
    db.session.add(new_company)
    db.session.commit()
    return jsonify({"message": "Company created successfully"})

@app.route("/companies/<id>", methods=["GET"])
def get_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    return jsonify({"id": company.id, "name": company.name})

@app.route("/companies/<id>", methods=["PUT"])
def update_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    data = request.get_json()
    company.name = data["name"]
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

@app.route("/job_ads/<id>", methods=["GET"])
def get_job_ads_by_id(id):
    ad = JobAds.query.get(id)
    if ad is None:
        return jsonify({"message": "job_ads not found"}), 404
    return jsonify({"id": ad.id, "company_name": ad.company_name, "poste": ad.poste, "lieu": ad.lieu, "salaire": ad.salaire, "contrat": ad.contrat, "description": ad.description})

@app.route("/job_ads/<id>", methods=["PUT"])
def update_job_ads(id):
    ad = JobAds.query.get(id)
    if ad is None:
        return jsonify({"message": "job_ads not found"}), 404
    data = request.get_json()
    ad.company_name = data["company_name"]
    ad.poste = data["poste"]
    ad.lieu = data["lieu"]
    ad.salaire = data["salaire"]
    ad.contrat = data["contrat"]
    ad.description = data["description"]
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

@app.route("/locations", methods=["GET"])
def get_locations():
    locations = db.session.query(func.substring_index(JobAds.city, ',', 1).label('city')).distinct().all()
    return jsonify({"locations": [location.city for location in locations]})

@app.route('/create_account_applier', methods=['POST'])
def create_user_applier():
    data = request.get_json()
    mail = data.get('mail')
    password = data.get('password')

    if not mail or not password:
        return jsonify({'error': 'Mail and password are required'}), 400

    hashed_password = generate_password_hash(password)  #pwd haché pour plus de sécu
    new_user = User(mail=mail, password=hashed_password)  
    db.session.add(new_user)
    db.session.commit()

    return jsonify({'message': 'User  created successfully', 'username': new_user.mail}), 201

@app.route('/create_account_company', methods=['POST'])
def create_user_company():
    data = request.get_json()
    name = data.get('name')
    password = data.get('password')

    if not name or not password:
        return jsonify({'error': 'Name and password are required'}), 400

    hashed_password = generate_password_hash(password)  #pwd haché pour plus de sécu
    new_user = User(name=name, password=hashed_password)  
    db.session.add(new_user)
    db.session.commit()

    return jsonify({'message': 'User  created successfully', 'username': new_user.name}), 201

@app.route('/verif_applier/<id>', methods=['GET'])
def get_user_applier(user_id):
    user = User.query.filter_by(id=user_id, user_type='is_applier').first()  # Assuming user_type distinguishes users
    if user is None:
        return jsonify({'error': 'User  not found'}), 404

    return jsonify({'id': user.id, 'username': user.mail}), 200

@app.route('/verif_company', methods=['GET'])
def get_user_company(user_id):
    user = User.query.filter_by(id=user_id, user_type='is_not_applier').first()  # Assuming user_type distinguishes users
    if user is None:
        return jsonify({'error': 'User  not found'}), 404

    return jsonify({'id': user.id, 'username': user.name}), 200

@app.route("/login", methods=["POST"])
def login():
    username = request.json["username"]
    password = request.json["password"]
    user = User.query.filter_by(username=username).first()
    if user and check_password_hash(user.password, password):  # Check hashed pwd
        login_user(user)
        return jsonify({"message": "Logged in successfully"}), 200
    return jsonify({"message": "Invalid credentials"}), 401

@app.route("/logout", methods=["POST"])
def logout():
    logout_user()
    return jsonify({"message": "Logged out successfully"}), 200

@app.route("/protected", methods=["GET"])
@login_required
def protected():
    return jsonify({"message": "Hello, authenticated user!"}), 200



# ... rest of your API routes ...

if __name__ == "__main__":
    app.run(debug=True, port=8000)