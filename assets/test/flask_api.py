from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://username:@localhost/web_project"  # a remplacer avec nos base de donnée
db = SQLAlchemy(app)

# personnes

class People(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    applying = db.Column(db.Integer, nullable=False)
    mail = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(100))
    firstname = db.Column(db.String(100))
    phone = db.Column(db.String(20))
    password = db.Column(db.String(255))
    salt = db.Column(db.String(255))

# companies

class Companies(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False, unique=True)
    username = db.Column(db.String(100), nullable=False)
    password = db.Column(db.String(255), nullable=False)
    salt = db.Column(db.String(255), nullable=False)
    
# Ad

class Advertising(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    company_name = db.Column(db.String(100), db.ForeignKey('companies.name'))
    poste = db.Column(db.String(255), nullable=False)
    lieu = db.Column(db.String(255), nullable=False)
    salaire = db.Column(db.String(20), nullable=False)
    contrat = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    
# storage

class RequestStorage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    id_annonce_postule = db.Column(db.Integer, db.ForeignKey('advertising.id'))
    applayer_info = db.Column(db.Integer, db.ForeignKey('people.id'))
    id_company = db.Column(db.Integer, db.ForeignKey('companies.id'))


                            #======================ADMIN======================
                            
@app.route("/users", methods=["GET"])
def get_users():
    users = User.query.all()
    output = [{"id": user.id, "name": user.name, "email": user.email} for user in users]
    return jsonify({"users": output})

@app.route("/users", methods=["POST"])
def create_user():
    data = request.get_json()
    new_user = User(name=data["name"], email=data["email"])
    db.session.add(new_user)
    db.session.commit()
    return jsonify({"message": "User  created successfully"})

@app.route("/users/<id>", methods=["GET"])
def get_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    return jsonify({"id": user.id, "name": user.name, "email": user.email})

@app.route("/users/<id>", methods=["PUT"])
def update_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    data = request.get_json()
    user.name = data["name"]
    user.email = data["email"]
    db.session.commit()
    return jsonify({"message": "User  updated successfully"})

@app.route("/users/<id>", methods=["DELETE"])
def delete_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    db.session.delete(user)
    db.session.commit()
    return jsonify({"message": "User  deleted successfully"})

if __name__ == "__main__":
    app.run(debug=True)
    
                         #======================Applyer======================
                         
@app.route("/users", methods=["GET"])
def get_users():
    users = User.query.all()
    output = [{"id": user.id, "name": user.name, "email": user.email} for user in users]
    return jsonify({"users": output})

@app.route("/users", methods=["POST"])
def create_user():
    data = request.get_json()
    new_user = User(name=data["name"], email=data["email"])
    db.session.add(new_user)
    db.session.commit()
    return jsonify({"message": "User  created successfully"})

@app.route("/users/<id>", methods=["GET"])
def get_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    return jsonify({"id": user.id, "name": user.name, "email": user.email})

@app.route("/users/<id>", methods=["PUT"])
def update_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    data = request.get_json()
    user.name = data["name"]
    user.email = data["email"]
    db.session.commit()
    return jsonify({"message": "User  updated successfully"})

                    #======================Company======================
                    
@app.route("/users", methods=["GET"])
def get_users():
    users = User.query.all()
    output = [{"id": user.id, "name": user.name, "email": user.email} for user in users]
    return jsonify({"users": output})

@app.route("/users", methods=["POST"])
def create_user():
    data = request.get_json()
    new_user = User(name=data["name"], email=data["email"])
    db.session.add(new_user)
    db.session.commit()
    return jsonify({"message": "User  created successfully"})

@app.route("/users/<id>", methods=["GET"])
def get_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    return jsonify({"id": user.id, "name": user.name, "email": user.email})

@app.route("/users/<id>", methods=["PUT"])
def update_user(id):
    user = User.query.get(id)
    if user is None:
        return jsonify({"message": "User  not found"}), 404
    data = request.get_json()
    user.name = data["name"]
    user.email = data["email"]
    db.session.commit()
    return jsonify({"message": "User  updated successfully"})