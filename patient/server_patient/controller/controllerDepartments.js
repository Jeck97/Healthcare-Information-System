const { getDepartments } = require("./serviceDepartments")

module.exports = {
    getDepartments: (req, res) => {
        getDepartments()
            .then(departments => {
                departments = departments.map(el => el = reformatKeys(el))
                res.status(200).json({ departments });
            })
            .catch(err => res.status(200).json({ err }))
    }
}

function reformatKeys(data) {
    for (let key in data) {
        let values = key.split('_').slice(1);
        values = values.map((val, i) => {
            if (i === 0) return val;
            return val.charAt(0).toUpperCase() + val.slice(1);
        })
        data[values.join('')] = data[key];
        delete data[key];
    }
    return data;
}