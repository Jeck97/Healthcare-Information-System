const { getPatients, updatePatient, insertPatient, getPatient } = require("./servicePatient");

module.exports = {
    getPatients: (req, res) => {
        getPatients()
            .then((patients) => {
                patients = patients.map(patient => reformatKeys(patient))
                res.status(200).json({ patients })
            })
            .catch((err) => res.status(400).json({ err }));
    },
    /**
     * method: GET
     * path: localhost:5000/api/patient/:id
     */
    getPatient: (req, res) => {

        let key = Object.keys(req.query);

        getPatient(key[0], req.query[key])
            .then((rst) => {
                let result = rst[0];
                let patient = reformatKeys(result)
                return res.status(200).json({ patient, unique: patient ? false : true })
            })
            .catch((err) => res.status(404).json({ err }));
    },
    updatePatient: (req, res, next) => {
        let patient = req.body;

        updatePatient(patient)
            .then((result) => {
                next();
            })
            .catch((err) => {
                console.log(err);
                res.status(400).json({ err })
            });
    },
    insertPatient: (req, res, next) => {
        let patient = req.body;
        console.log(patient);
        insertPatient(patient)
            .then((result) => {
                req.body.id = result.insertId
                next()
            })
            .catch((err) => {
                res.status(409).json({
                    message: err.message,
                    err,
                });
            });
    },

};

function reformatKeys(data) {
    for (let key in data) {
        let values = key.split('_').slice(1);
        values = values.map((val, i) => {
            if (i === 0) return val;
            return val.charAt(0).toUpperCase() + val.slice(1)
        })
        data[values.join('')] = data[key]
        delete data[key]
    }
    return data
}