import axios from '../config/axios.js';
// TODO POST request -> return visit, take medicine
// TODO
new Vue({
    el: '#app',
    name: 'Queue',
    created() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        let patientId = params.get('patient_id');
        let insuranceId = params.get('insurance_id');
        this.queue.patientId = patientId;
        this.queue.insuranceId = insuranceId;
        if (params.get('type') === 'old') this.searchHistory(patientId);
    },
    mounted() {
        axios
            .get('/departments')
            .then(response => (this.database.departments = response.data.departments))
            .catch(err => console.log(err));
    },
    data: {
        queue: {
            patientId: '',
            type: '',
            returnVisit: '',
            takeMedicine: '',
            consultationReason: '',
            insuranceId: '',
            departmentId: '',
        },
        init: {
            queue: {
                types: [
                    { name: 'consultation', available: true, value: 0 },
                    { name: 'return visit', available: false, value: 1 },
                    { name: 'take medicine', available: false, value: -1 },
                ],
            },
        },
        database: {
            visits: [],
            medicines: [],
            departments: [],
        },
        response: {
            data: {
                medicines: [
                    { name: 'paracetamol', id: 1 },
                    { name: 'ibuprofen', id: 2 },
                ],
                visits: [
                    { name: 'fever', id: 1 },
                    { name: 'headache', id: 2 },
                ],
            },
        },
        dirty: {
            queue: {
                type: false,
                returnVisit: false,
                takeMedicine: false,
                consultationReason: false,
                insuranceId: false,
                departmentId: false,
            },
        },
    },
    methods: {
        searchHistory(patientId) {
            axios
                .get(`/queue?patient_id=${patientId}`)
                .then(response => this.processQueue(response.data.rst))
                .catch(err => console.log(err));
        },
        processQueue(queue) {
            console.log(queue);
            queue = Object.entries(queue).map(item => item[1]);
            let medicines = queue.filter(item => item.order_quantity !== null || item.order_quantity > 0);
            let visits = queue.filter(item => item.revisit_status === 1);
            if (medicines.length > 0) this.setMedicines(medicines);
            if (visits.length > 0) this.setVisits(visits);
        },
        setMedicines(medicines) {
            let found = this.init.queue.types.find(type => type.name === 'take medicine');

            found.available = true;

            this.database.medicines = medicines.map(medicine => {
                return {
                    id: medicine.consultation_id,
                    name: medicine.drug_name,
                    departmentId: medicine.department_id,
                    consultationReason: medicine.consultation_reason,
                };
            });
        },
        setVisits(visits) {
            let found = this.init.queue.types.find(type => type.name === 'return visit');
            found.available = true;
            this.database.visits = visits.map(visit => {
                return {
                    id: visit.consultation_id,
                    name: visit.consultation_reason,
                    departmentId: visit.department_id,
                    time: visit.consultation_date_time,
                    consultationReason: visit.consultation_reason,
                };
            });
        },
        selectQueue() {
            this.clearInput();
            if (this.queue.type === -1) this.setDepartmentPharmacyId();
        },
        selectVisitDepartment() {
            let visit = this.database.visits.find(visit => (visit.id = this.queue.returnVisit));
            console.log(visit);
            this.queue.departmentId = visit.departmentId;
            this.queue.consultationReason = visit.consultationReason;
        },
        selectTakeMedicine() {
            let medicine = this.database.medicines.find(medicine => (medicine.id = this.queue.takeMedicine));
            this.queue.consultationReason = medicine.consultationReason;
        },
        clearInput() {
            this.queue.returnVisit = '';
            this.queue.takeMedicine = '';
            this.queue.departmentId = '';
            this.queue.consultationReason = '';
        },
        onSubmit(e) {
            let invalidKeys = this.getInvalidKeys();
            if (invalidKeys.length > 0)
                return alert(
                    invalidKeys.join(', ').replace(/, ([^,]*)$/, ' and $1') +
                    ' is invalid. Please check again before submit form.'
                );
            this.requestQueue();
        },
        getInvalidKeys() {
            let validations = this.validations;
            let filter = [];

            const iterate = obj => {
                filter.push(
                    Object.keys(obj).filter(key => {
                        if (typeof obj[key] === 'object') {
                            iterate(obj[key]);
                        } else if (obj[key] === false) {
                            return key;
                        }
                    })
                );
            };

            iterate(validations);
            return filter.flat().map(el => this.camelToNormal(el));
        },
        requestQueue() {
            axios
                .post('/queue', this.queue)
                .then(response => {
                    alert('Register Successfully.');
                    window.location.href = '/his/patient/view/patient.html';
                })
                .catch(err => {
                    console.log(err);
                });
        },
        setDepartmentPharmacyId() {
            let pharmacyDepartment = this.database.departments.find(
                department => department.name.toLowerCase() === 'pharmacy'
            );
            this.queue.departmentId = pharmacyDepartment.id;
        },
    },
    computed: {
        capitalize() {
            return val => val.charAt(0).toUpperCase() + val.slice(1);
        },
        camelToNormal() {
            return val =>
                val
                .replace(/([A-Z])/g, ' $1')
                .charAt(0)
                .toUpperCase() + val.replace(/([A-Z])/g, ' $1').slice(1);
        },
        setMedicines(medicines) {
            this.database.medicines = medicines;
        },
        setVisits(visits) {
            this.database.visits = visits;
        },
        getMedicines() {
            return this.database.medicines;
        },
        getReturnVisits() {
            return this.database.visits;
        },
        isEmpty() {
            return val => val.toString().trim().length === 0 || val.toString().trim() === null || val === undefined;
        },
        $v() {
            return {
                patientId: {
                    error: !this.validations.patientId,
                    valid: this.validations.patientId,
                },
                type: {
                    error: !this.validations.type,
                    valid: this.validations.type,
                },
                returnVisit: {
                    error: !this.validations.returnVisit,
                    valid: this.validations.returnVisit.requiredIf,
                },
                takeMedicine: {
                    error: !this.validations.takeMedicine,
                    valid: this.validations.takeMedicine.requiredIf,
                },
                consultationReason: {
                    error: !this.validations.consultationReason,
                    valid: this.validations.consultationReason,
                },
                departmentId: {
                    error: !this.validations.departmentId,
                    valid: this.validations.departmentId,
                },
            };
        },
        validations() {
            return {
                patientId: !this.isEmpty(this.queue.patientId),
                type: !this.isEmpty(this.queue.type),
                returnVisit: this.database.visits.length > 0 && this.queue.type === 1 ? !this.isEmpty(this.queue.returnVisit) : true,
                takeMedicine: this.database.medicines.length > 0 && this.queue.type === -1 ? !this.isEmpty(this.queue.takeMedicine) : true,
                consultationReason: !this.isEmpty(this.queue.consultationReason),
                departmentId: !this.isEmpty(this.queue.departmentId) && this.dirty.queue.departmentId,
            };
        },
    },
    watch: {
        'queue.type': function() {
            if (!this.dirty.queue.type) this.dirty.queue.type = true;
        },
        'queue.returnVisit': function() {
            if (!this.dirty.queue.returnVisit) this.dirty.queue.returnVisit = true;
        },
        'queue.takeMedicine': function() {
            if (!this.dirty.queue.takeMedicine) this.dirty.queue.takeMedicine = true;
        },
        'queue.consultationReason': function() {
            if (!this.dirty.queue.consultationReason) this.dirty.queue.consultationReason = true;
        },
        'queue.departmentId': function() {
            if (!this.dirty.queue.departmentId) this.dirty.queue.departmentId = true;
        },
    },
});