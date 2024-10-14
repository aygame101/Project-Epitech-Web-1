from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "mysql://username:password@localhost/mydatabase"   # a remplacer avec nos base de donnée

#pareil en dessus a adapter avec notre db

db = SQLAlchemy(app)

# a modif selon la database

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(255), nullable=False)
    email = db.Column(db.String(255), nullable=False, unique=True)

# a repéter avec les differents profils et par conséquent à adapter (postulant[creer, lire] , entreprises[créer, lire, modifier] et admin[créer, lire, modifier, supprimer])

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