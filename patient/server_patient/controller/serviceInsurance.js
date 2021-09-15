const pool = require("../config/database");

module.exports = {
    insertInsurance: (policyNumber, patientId) => {
        return new Promise((resolve, reject) => {
            let query = `INSERT INTO insurance (policy_number, patient_id)
                VALUES ('${policyNumber}', '${patientId}')`;

            pool.query(query, (err, result) => {
                console.log(err, result);
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    getInsurance: (policyNumber, patientId) => {
        return new Promise((resolve, reject) => {
            let query = `SELECT * FROM insurance WHERE policy_number = '${policyNumber}' AND patient_id = ${patientId}`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    getInsuranceByPolicyNumber: (policyNumber) => {

        return new Promise((resolve, reject) => {
            let query = `SELECT * FROM insurance WHERE policy_number = '${policyNumber}'`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    getInsuranceById: (patientId) => {
        return new Promise((resolve, reject) => {
            let query = `SELECT * FROM insurance WHERE patient_id = ${patientId}`;

            pool.query(query, (err, result) => {

                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    deleteInsuranceByPolicyNumber: (policyNumber) => {
        return new Promise((resolve, reject) => {
            let query = `DELETE FROM insurance WHERE policy_number = ${policyNumber}`;
            console.log(query);
            pool.query(query, (err, result) => {
                console.log(err, result);
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    updateInsuranceId: (queueId, policyNumber, callback) => {
        let query = `UPDATE bill set insurance_id = (select insurance_id from insurance where policy_number = ${policyNumber})
            WHERE consultation_id = (select consultation_id FROM consultation WHERE queue_id = ${queueId})`;

        pool.query(query, (err, result) => {
            if (err) return callback(err);
            callback(false, result);
        });
    }
};