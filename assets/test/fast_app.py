


    id = db.Column(db.Integer, primary_key=True)

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False, unique=True)
    username = db.Column(db.String(100), nullable=False)
    password = db.Column(db.String(255), nullable=False)
    salt = db.Column(db.String(255), nullable=False)
    company_name = db.Column(db.String(100), db.ForeignKey('companies.name'))
    poste = db.Column(db.String(255), nullable=False)
    lieu = db.Column(db.String(255), nullable=False)
    salaire = db.Column(db.String(20), nullable=False)
    contrat = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    id_annonce_postule = db.Column(db.Integer, db.ForeignKey('advertising.id'))
    applayer_info = db.Column(db.Integer, db.ForeignKey('people.id'))
    id_company = db.Column(db.Integer, db.ForeignKey('companies.id'))

    data = request.get_json()
    db.session.commit()
    db.session.commit()
    db.session.commit()

    data = request.get_json()
    db.session.commit()
    db.session.commit()

    data = request.get_json()
    db.session.commit()
    db.session.commit()
