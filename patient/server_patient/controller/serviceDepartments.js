const pool = require("../config/database");

module.exports = {

    getDepartments: () => {
        return new Promise((resolve, reject) => {
            let query = `SELECT * FROM department`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },

};