const router = require("express").Router();
const { getDepartments } = require("./controllerDepartments");
const { manageInsurance, getInsurances, deleteInsuranceByPolicyNumber } = require("./controllerInsurance");
const { getPatients, updatePatient, insertPatient, getPatient } = require("./controllerPatient");
const { manageQueue, getVisitsAndOrderedMedicines } = require("./controllerQueue");

router.get("/patients", getPatients);
router.get("/patient", getPatient);
router.put("/patient", updatePatient, manageInsurance);
router.post("/patient", insertPatient, manageInsurance);

router.post('/insurance', manageInsurance);
router.get('/insurance', getInsurances);
router.put('/insurance', deleteInsuranceByPolicyNumber);

router.post('/queue', manageQueue);
router.get('/queue', getVisitsAndOrderedMedicines);

router.get('/departments', getDepartments);

module.exports = router;