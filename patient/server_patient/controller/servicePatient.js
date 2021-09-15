const pool = require("../config/database");

module.exports = {
    getPatients: () => {
        return new Promise((res, rej) => {
            pool.query("SELECT * FROM PATIENT", (err, result) => {
                if (err) return rej(err);
                return res(result);
            });
        });
    },
    getPatient: (key, value) => {
        return new Promise((res, rej) => {
            pool.query(
                `SELECT * FROM PATIENT WHERE ${key} = '${value}'`,
                (err, result) => {
                    if (err) return rej(err);
                    return res(result);
                }
            );
        });
    },
    // todo: MySQL query
    updatePatient: (patient) => {

        return new Promise((resolve, reject) => {
            let query = `UPDATE patient SET 
            patient_first_name = '${patient.name.first}', 
            patient_last_name = '${patient.name.last}', 
            patient_dob = "${patient.dateOfBirth}", 
            patient_gender = "${patient.gender}", 
            patient_identification_number = "${patient.identificationNumber}", 
            patient_email = "${patient.email}", 
            patient_phone_number = "${patient.phoneNumber}", 
            patient_detail_address = "${patient.address.detail}",
            patient_area = "${patient.address.area}", 
            patient_postcode = "${patient.address.postcode}", 
            patient_state = "${patient.address.state}", 
            patient_country = "${patient.address.country}", 
            patient_blood_type = "${patient.medical.bloodType}", 
            patient_blood_rhesus = "${patient.medical.bloodRhesus}", 
            patient_organ_donate = "${patient.medical.organDonate}", 
            patient_next_of_kin_name = "${patient.medical.nextOfKin.name}", 
            patient_next_of_kin_relationship = "${patient.medical.nextOfKin.relationship}",
            patient_next_of_kin_phone_number_home = "${patient.medical.nextOfKin.phoneNumberHome}", 
            patient_next_of_kin_phone_number_mobile = "${patient.medical.nextOfKin.phoneNumberMobile}" 
            WHERE patient.patient_id = ${patient.id}`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },
    insertPatient: (patient) => {
        return new Promise((resolve, reject) => {
            let query = `INSERT INTO patient (
                patient_first_name, patient_last_name, patient_dob, 
                patient_gender, patient_email, patient_phone_number, 
                patient_identification_number, patient_detail_address, patient_state, 
                patient_area, patient_postcode, patient_country,
                patient_blood_type, patient_blood_rhesus, patient_organ_donate,
                patient_next_of_kin_name, patient_next_of_kin_relationship,
                patient_next_of_kin_phone_number_home, patient_next_of_kin_phone_number_mobile) 
                VALUES (
                '${patient.name.first}', '${patient.name.last}', '${patient.dateOfBirth}', 
                '${patient.gender}', '${patient.email}', ${patient.phoneNumber}, 
                ${patient.identificationNumber}, '${patient.address.detail}', '${patient.address.state}', 
                '${patient.address.area}', ${patient.address.postcode}, '${patient.address.country}', 
                '${patient.medical.bloodType}', '${patient.medical.bloodRhesus}', '${patient.medical.organDonate}', 
                '${patient.medical.nextOfKin.name}','${patient.medical.nextOfKin.relationship}',
                '${patient.medical.nextOfKin.phoneNumberHome}','${patient.medical.nextOfKin.phoneNumberMobile}')`;

            pool.query(query, (err, result) => {
                if (err) return reject(err);
                resolve(result);
            });
        });
    },

};