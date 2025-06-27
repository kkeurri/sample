const { Client } = require('pg');

exports.handler = async (event) => {
  if (event.httpMethod !== 'POST') {
    return { statusCode: 405, body: 'Method Not Allowed' };
  }

  const data = JSON.parse(event.body);
  const { fname, lname, contact, email, password } = data;

  const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
  const unique = Math.floor(10000 + Math.random() * 90000); // 5-digit random number
  const user_id = `U${date}-${unique}`;

  const client = new Client({
    host: 'ep-bitter-violet-a1ija7b4-pooler.ap-southeast-1.aws.neon.tech',
    database: 'user',
    user: 'user_owner',
    password: 'npg_jBkWc71zHKCE',
    ssl: { rejectUnauthorized: false }
  });

  try {
    await client.connect();
    await client.query(`
      INSERT INTO users (user_id, first_name, last_name, contact_num, email, password)
      VALUES ($1, $2, $3, $4, $5, $6)
    `, [user_id, fname, lname, contact, email, password]);

    return {
      statusCode: 200,
      body: JSON.stringify({ success: true, user_id })
    };
  } catch (err) {
    return {
      statusCode: 500,
      body: JSON.stringify({ success: false, message: err.message })
    };
  } finally {
    await client.end();
  }
};
