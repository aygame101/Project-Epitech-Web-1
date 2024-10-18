from flask import Flask, request, jsonify
from flask_login import login_user, logout_user, login_required
from werkzeug.security import generate_password_hash, check_password_hash
from flask_sqlalchemy import SQLAlchemy
from flask_sqlalchemy  import User 
from sqlalchemy import func

app = Flask(__name__)
db = SQLAlchemy(app)

app = Flask(__name__)

class People(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    is_applier = db.Column(db.Integer, nullable=False)
    mail = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(100))
    firstname = db.Column(db.String(100))
    phone = db.Column(db.String(20))
    password = db.Column(db.String(255))
    salt = db.Column(db.String(255))

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

if __name__ == "__main__":
    app.run(debug=True)