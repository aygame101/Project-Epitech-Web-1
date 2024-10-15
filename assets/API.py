from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://root:@localhost/web_project"  # replace with your database credentials
db = SQLAlchemy(app)

# Define models
class People(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    applying = db.Column(db.Integer, nullable=False)
    mail = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(100))
    firstname = db.Column(db.String(100))
    phone = db.Column(db.String(20))
    password = db.Column(db.String(255))
    salt = db.Column(db.String(255))

class Companies(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False, unique=True)
    username = db.Column(db.String(100), nullable=False)
    password = db.Column(db.String(255), nullable=False)
    salt = db.Column(db.String(255), nullable=False)

class Advertising(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    company_name = db.Column(db.String(100), db.ForeignKey('companies.name'))
    poste = db.Column(db.String(255), nullable=False)
    lieu = db.Column(db.String(255), nullable=False)
    salaire = db.Column(db.String(20), nullable=False)
    contrat = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)

class RequestStorage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    id_annonce_postule = db.Column(db.Integer, db.ForeignKey('advertising.id'))
    applayer_info = db.Column(db.Integer, db.ForeignKey('people.id'))
    id_company = db.Column(db.Integer, db.ForeignKey('companies.id'))

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
    output = [{"id": company.id, "name": company.name, "username": company.username} for company in companies]
    return jsonify({"companies": output})

@app.route("/companies", methods=["POST"])
def create_company():
    data = request.get_json()
    new_company = Companies(name=data["name"], username=data["username"], password=data["password"], salt=data["salt"])
    db.session.add(new_company)
    db.session.commit()
    return jsonify({"message": "Company created successfully"})

@app.route("/companies/<id>", methods=["GET"])
def get_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    return jsonify({"id": company.id, "name": company.name, "username": company.username})

@app.route("/companies/<id>", methods=["PUT"])
def update_company(id):
    company = Companies.query.get(id)
    if company is None:
        return jsonify({"message": "Company not found"}), 404
    data = request.get_json()
    company.name = data["name"]
    company.username = data["username"]
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

@app.route("/advertising", methods=["GET"])
def get_advertising():
    advertising = Advertising.query.all()
    output = [{"id": ad.id, "company_name": ad.company_name, "poste": ad.poste, "lieu": ad.lieu, "salaire": ad.salaire, "contrat": ad.contrat, "description": ad.description} for ad in advertising]
    return jsonify({"advertising": output})

@app.route("/advertising", methods=["POST"])
def create_advertising():
    data = request.get_json()
    new_ad = Advertising(company_name=data["company_name"], poste=data["poste"], lieu=data["lieu"], salaire=data["salaire"], contrat=data["contrat"], description=data["description"])
    db.session.add(new_ad)
    db.session.commit()
    return jsonify({"message": "Advertising created successfully"})

@app.route("/advertising/<id>", methods=["GET"])
def get_advertising_by_id(id):
    ad = Advertising.query.get(id)
    if ad is None:
        return jsonify({"message": "Advertising not found"}), 404
    return jsonify({"id": ad.id, "company_name": ad.company_name, "poste": ad.poste, "lieu": ad.lieu, "salaire": ad.salaire, "contrat": ad.contrat, "description": ad.description})

@app.route("/advertising/<id>", methods=["PUT"])
def update_advertising(id):
    ad = Advertising.query.get(id)
    if ad is None:
        return jsonify({"message": "Advertising not found"}), 404
    data = request.get_json()
    ad.company_name = data["company_name"]
    ad.poste = data["poste"]
    ad.lieu = data["lieu"]
    ad.salaire = data["salaire"]
    ad.contrat = data["contrat"]
    ad.description = data["description"]
    db.session.commit()
    return jsonify({"message": "Advertising updated successfully"})

@app.route("/advertising/<id>", methods=["DELETE"])
def delete_advertising(id):
    ad = Advertising.query.get(id)
    if ad is None:
        return jsonify({"message": "Advertising not found"}), 404
    db.session.delete(ad)
    db.session.commit()
    return jsonify({"message": "Advertising deleted successfully"})

if __name__ == "__main__":
    app.run(debug=True, port=8000)