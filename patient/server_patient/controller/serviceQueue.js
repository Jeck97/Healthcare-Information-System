const pool = require("../config/database");

module.exports = {
    createQueue: (patientId, departmentId, queueType, oldConsultationId) => {
        return new Promise((res, rej) => {
            let statement = ''
            if (queueType === 0)
                statement = `INSERT INTO queue (department_id, patient_id, queue_type) 
            VALUES (${departmentId}, ${patientId}, ${queueType})`;
            else
                statement = `INSERT INTO queue (department_id, patient_id, queue_type, old_consultation_id) 
            VALUES (${departmentId}, ${patientId}, ${queueType}, ${oldConsultationId})`;

            pool.query(statement,
                (err, result) => {
                    if (err) return rej(err);
                    return res(result);
                }
            );
        });
    },
    updateRevisitStatusByConsultationId: (consultationId) => {
        return new Promise((resolve, reject) => {
            let query = `UPDATE consultation SET 
            revisit_status = 0, 
           
            WHERE patient.consultation_id = ${consultationId}`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    getVisitsAndOrderedMedicines: function(patientId) {
        return new Promise((resolve, reject) => {

            let query = "SELECT patient_id, consultation_id, consultation_reason, revisit_status, `order`.order_id, order_quantity, department_id, drug_name, consultation_date_time FROM `consultation` LEFT JOIN `order` ON `order`.`order_id` = consultation.order_id LEFT JOIN order_drug ON order_drug.order_id = `order`.`order_id` LEFT JOIN drug ON drug.drug_id = order_drug.drug_id NATURAL JOIN queue NATURAL JOIN patient WHERE (order_quantity > 0 OR revisit_status = 1) AND patient.patient_id =" + patientId;
            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });

        })

    }
};