const pool = require("../config/database");


function updateConsultationReason(queueId, consultationReasion, callback) {
    let statement = `update consultation set consultation_reason ='${consultationReasion}'
    WHERE queue_id = ${queueId}`;
    pool.query(statement, (err, result) => {
        if (err) return callback(err);
        callback(false, result);
    })
}

function updateRevisitStatusByConsultationId(consultationId) {
    return new Promise((resolve, reject) => {
        let query = `UPDATE consultation SET revisit_status = 0 WHERE consultation_id = ${consultationId}`;
        console.log(query);
        pool.query(query, (err, result) => {
            if (err) return reject(err);
            resolve(result);
        });
    });
}

module.exports = { updateConsultationReason, updateRevisitStatusByConsultationId }