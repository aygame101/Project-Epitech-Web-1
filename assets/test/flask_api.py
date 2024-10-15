from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.sql import text

# this variable, db, will be used for all SQLAlchemy commands
db = SQLAlchemy()
# create the app
app = Flask(__name__)
# change string to the name of your database; add path if necessary
db_name = 'db.py'

app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///' + db_name

app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = True

app.app_context(db_name)

# initialize the app with Flask-SQLAlchemy
db.init_app(app)


# NOTHING BELOW THIS LINE NEEDS TO CHANGE
# this route will test the database connection - and nothing more
@app.route('/')
def testdb():
    try:
        db.session.query(text('1')).from_statement(text('SELECT 1')).all()
        return 'It work'
    except Exception as e:
        # e holds description of the error
        error_text = "The error:" + str(e) 
        hed = ('is broken')
        return hed + error_text

if __name__ == '__main__':
    app.run(debug=True)

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(255), nullable=False)
    email = db.Column(db.String(255), nullable=False, unique=True)
    
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

# a repéter avec les differents profils et par conséquent à adapter (postulant[creer, lire] , entreprises[créer, lire, modifier] et admin[créer, lire, modifier, supprimer])

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
    
'''                     #======================Applyer======================
                         
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


#======================TEST PART======================

#import pytest '''
