const { updateConsultationReason, updateRevisitStatusByConsultationId } = require('./serviceConsultation');
const { createQueue, getVisitsAndOrderedMedicines } = require('./serviceQueue');
const { updateInsuranceId } = require('./serviceInsurance');

module.exports = {
    manageQueue: (req, res) => {
        let data = req.body;
        let type = data.type;
        // -1 take medicine, 0 consultation,  1 return visit
        switch (type) {
            case -1:
                createQueue(data.patientId, data.departmentId, data.type, data.takeMedicine)
                    .then(rst => res.status(200).json({ insertedId: rst.insertId }))
                    .catch(err => console.log('createQueue', err));
                break;
            case 1:
                // consultationId: data.returnVisit
                updateRevisitStatusByConsultationId(data.returnVisit)
                    .then(rst => console.log(rst))
                    .catch(err => console.log('updateRevisitStatusByConsultationId:', err));

                createQueue(data.patientId, data.departmentId, data.type, data.returnVisit)
                    .then(rst => {
                        console.log('queue:', rst);
                        let queueId = rst.insertId;
                        let consultationReason = data.consultationReason;
                        let insuranceId = data.insuranceId;
                        updateConsultationReason(queueId, consultationReason, function(err, result) {
                            if (err) return res.status(200).json({ err });
                            if (data.insuranceId)
                                updateInsuranceId(queueId, insuranceId, function(err, result) {
                                    if (err) return res.status(200).json({ err });
                                    res.status(200).json({ result });
                                });
                            else return res.status(200).json({ result });
                        });
                    })
                    .catch(err => console.log('createQueue', err));
                break;
            default:
                // get queue id to create consultation record(consultation reason)
                createQueue(data.patientId, data.departmentId, data.type)
                    .then(rst => {
                        let queueId = rst.insertId;
                        let consultationReason = data.consultationReason;
                        let insuranceId = data.insuranceId;
                        return updateConsultationReason(queueId, consultationReason, function(err, result) {
                            if (err) return res.status(200).json({ err });
                            if (data.insuranceId)
                                return updateInsuranceId(queueId, insuranceId, function(err, result) {
                                    console.log('updateInsuranceId', err);
                                    console.log('updateInsuranceId', result);
                                    if (err) return res.status(200).json({ err });
                                    return res.status(200).json({ result });
                                });
                            else return res.status(200).json({ result });
                        });
                    })
                    .catch(err => console.log('createQueue', err));
        }
    },
    getVisitsAndOrderedMedicines: function(req, res) {
        let patientId = req.query.patient_id;
        getVisitsAndOrderedMedicines(patientId)
            .then(rst => res.status(200).json({ rst }))
            .catch(err => console.log('getVisitsAndOrderedMedicines', err));
    },
};

function reformatKeys(data) {
    for (let key in data) {
        let values = key.split('_');
        values = values.map((val, i) => {
            if (i === 0) return val;
            return val.charAt(0).toUpperCase() + val.slice(1);
        });

        data[values.join('')] = data[key];
        delete data[key];
    }
    return data;
}