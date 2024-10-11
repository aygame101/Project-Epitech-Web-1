const express = require('express');
const { Pool } = require('pg');

const app = express();
const pool = new Pool({
  user: 'your_username',
  host: 'your_host', //a modif
  database: 'your_database',
  password: 'your_password',
  port: 5500, //a modif
});

// Create an user
app.post('/users', (req, res) => {
  const { name, email, password } = req.body;
  const query = {
    text: `INSERT INTO users (name, email, password) VALUES ($1, $2, $3) RETURNING *`,
    values: [name, email, password],
  };
  pool.query(query, (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).send({ message: 'Error creating user' });
    } else {
      res.status(201).send({ id: result.rows[0].id });
    }
  });
});

// Read the user
app.get('/users', (req, res) => {
  const query = {
    text: 'SELECT * FROM users',
  };
  pool.query(query, (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).send({ message: 'Error retrieving users' });
    } else {
      res.status(200).send(result.rows);
    }
  });
});

app.get('/users/:id', (req, res) => {
  const id = req.params.id;
  const query = {
    text: 'SELECT * FROM users WHERE id = $1',
    values: [id],
  };
  pool.query(query, (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).send({ message: 'Error retrieving user' });
    } else {
      res.status(200).send(result.rows[0]);
    }
  });
});

// Update the user
app.put('/users/:id', (req, res) => {
  const id = req.params.id;
  const { name, email, password } = req.body;
  const query = {
    text: `UPDATE users SET name = $1, email = $2, password = $3 WHERE id = $4 RETURNING *`,
    values: [name, email, password, id],
  };
  pool.query(query, (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).send({ message: 'Error updating user' });
    } else {
      res.status(200).send(result.rows[0]);
    }
  });
});

// Delete kapoot le user
app.delete('/users/:id', (req, res) => {
  const id = req.params.id;
  const query = {
    text: 'DELETE FROM users WHERE id = $1',
    values: [id],
  };
  pool.query(query, (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).send({ message: 'Error deleting user' });
    } else {
      res.status(204).send();
    }
  });
});

app.listen(3000, () => {
  console.log('API listening on port 3000');
});