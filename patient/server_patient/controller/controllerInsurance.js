const {
    insertInsurance,
    getInsurance,
    getInsuranceByPolicyNumber,
    getInsuranceById,
    deleteInsuranceByPolicyNumber
} = require("./serviceInsurance");




module.exports = {

    manageInsurance: (req, res) => {
        let policyNumber = req.body.medical.insuranceId;
        let patientId = req.body.id;
        // check duplication policy number
        if (policyNumber)
            return getInsurance(policyNumber, patientId)
                .then((result) => {
                    // create insurance record
                    if (result.length === 0)
                        return insertInsurance(policyNumber, patientId)
                            .then(result => {
                                return res.status(200).json({ success: 1, patientId })
                            })
                            .catch(err => {
                                return res.status(200).json({ success: 0, err })
                            });

                    res.status(200).json({ success: 1, patientId })
                })
                .catch((err) => {
                    res.status(409).json({
                        message: err.message,
                        err,
                    });
                });
        return res.status(200).json({ success: 1, patientId })
    },
    getInsurances: (req, res) => {
        let keys = Object.keys(req.query)
        switch (keys[0]) {
            case 'policy_number':
                getInsuranceByPolicyNumber(req.query.policy_number)
                    .then((rst) => {
                        return res.status(200).json({ unique: rst.length > 0 ? false : true })
                    })
                    .catch((err) => res.status(404).json({ err }));
                break;
            case 'patient_id':
                getInsuranceById(req.query.patient_id)
                    .then((rst) => {
                        rst = rst.map(el => el = reformatKeys(el))
                        return res.status(200).json({ rst })
                    })
                    .catch((err) => res.status(404).json({ err }));

                break;
        }
    },
    deleteInsuranceByPolicyNumber: (req, res) => {
        let policyNumber = req.query.policy_number;

        deleteInsuranceByPolicyNumber(policyNumber)
            .then(rst => res.status(200).json({ rst }))
            .catch(err => res.status(200).json({ err }))
    },

};

function reformatKeys(data) {
    for (let key in data) {
        let values = key.split('_');
        values = values.map((val, i) => {

            if (i === 0) return val;
            return val.charAt(0).toUpperCase() + val.slice(1)
        })

        data[values.join('')] = data[key]
        delete data[key]
    }
    return data
}