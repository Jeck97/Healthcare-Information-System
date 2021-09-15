import axios from '../config/axios.js';
new Vue({
    el: '#app',
    name: 'Patient',
    data: {
        title: 'Registration',
        patient: {
            id: '',
            name: {
                first: '',
                last: '',
            },
            dateOfBirth: '',
            gender: '',
            email: '',
            phoneNumber: '',
            identificationNumber: '',
            address: {
                detail: '',
                state: '',
                area: '',
                postcode: '',
                country: '',
            },
            medical: {
                bloodType: '',
                bloodRhesus: '',
                organDonate: '',
                insuranceId: '',
                nextOfKin: {
                    name: '',
                    relationship: '',
                    phoneNumberHome: '',
                    phoneNumberMobile: '',
                },
            },
        },
        database: {
            patient: {
                id: '',
                name: {
                    first: '',
                    last: '',
                },
                dateOfBirth: '',
                gender: '',
                email: '',
                phoneNumber: '',
                identificationNumber: '',
                address: {
                    detail: '',
                    state: '',
                    area: '',
                    postcode: '',
                    country: '',
                },
                medical: {
                    bloodType: '',
                    bloodRhesus: '',
                    organDonate: '',
                    insuranceId: '',
                    nextOfKin: {
                        name: '',
                        relationship: '',
                        phoneNumberHome: '',
                        phoneNumberMobile: '',
                    },
                },
            },
        },
        dirty: {
            patient: {
                name: {
                    first: false,
                    last: false,
                },
                dateOfBirth: false,
                gender: false,
                email: false,
                phoneNumber: false,
                identificationNumber: false,
                address: {
                    detail: false,
                    state: false,
                    area: false,
                    postcode: false,
                    country: false,
                },
                medical: {
                    bloodType: false,
                    bloodRhesus: false,
                    organDonate: false,
                    insuranceId: false,
                    nextOfKin: {
                        name: false,
                        relationship: false,
                        phoneNumberHome: false,
                        phoneNumberMobile: false,
                    },
                },
            },
        },
        init: {
            gender: ['male', 'female', 'prefer not to say'],
            departments: [],
            medical: {
                bloodTypes: ['A', 'AB', 'B', 'O'],
                bloodRhesus: [
                    { name: 'positive', value: 1 },
                    { name: 'negative', value: -1 },
                ],
                organDonate: [
                    { name: 'yes', value: 1 },
                    { name: 'no', value: 0 },
                ],
            },
            insurances: [],
            patient: {
                id: '',
                name: {
                    first: '',
                    last: '',
                },
                dateOfBirth: '',
                gender: '',
                email: '',
                phoneNumber: '',
                identificationNumber: '',
                address: {
                    detail: '',
                    state: '',
                    area: '',
                    postcode: '',
                    country: '',
                },
                medical: {
                    bloodType: '',
                    bloodRhesus: '',
                    organDonate: '',
                    insuranceId: '',
                    nextOfKin: {
                        name: '',
                        relationship: '',
                        phoneNumberHome: '',
                        phoneNumberMobile: '',
                    },
                },
            },
        },
        search: '',
        flagSearch: false,
        flagUniqueEmail: true,
        flagUniqueIdentificationNumber: true,
        flagUniquePhoneNumber: true,
        flagUniqueInsuranceId: true,
        flagInsuranceModal: false,
        editInsuranceId: '',
        metrics: {
            postcodeLength: 5,
        },
    },
    methods: {
        onClickSearch() {
            this.getPatientRequest('patient_identification_number', this.search, (err, result) => {
                if (result.data.patient) {
                    this.resetForm();
                    this.processPatient(result.data.patient);
                    this.getInsurances();
                    return;
                }
                this.flagSearch = false;
                alert('Patient not found');
            });
        },
        getPatientRequest(key, value, callback) {
            axios
                .get(`/patient?${key}=${value}`)
                .then(response => {
                    callback(false, response);
                })
                .catch(err => callback(err));
        },
        onSubmit(e) {
            let invalidKeys = this.getInvalidKeys();
            if (invalidKeys.length > 0)
                return alert(
                    invalidKeys.join(', ').replace(/, ([^,]*)$/, ' and $1') +
                    ' is invalid. Please check again before submit form.'
                );
            this.flagSearch ? this.updateRequest() : this.registerRequest();
        },
        resetForm() {
            this.iterate(this.patient);
        },
        iterate(target) {
            Object.keys(target).forEach(key => {
                if (typeof target[key] === 'object') return this.iterate(target[key]);
                target[key] = '';
            });
        },
        onClickEditInsurance() {
            console.log('edit');
            this.flagInsuranceModal = true;
        },
        onClickDeleteInsurance() {
            axios
                .put(`./insurance?policy_number=${this.editInsuranceId}`)
                .then(response => {
                    this.getInsurances();
                    this.flagInsuranceModal = false;
                    alert(`${this.editInsuranceId} is deleted successfully.`);
                })
                .catch(err => {
                    console.log(err);
                    alert(`Delete ${this.editInsuranceId} failed.`);
                });
        },
        onClickReset(e) {
            this.flagSearch = false;
            this.init.insurances = [];
            this.resetForm();
        },
        registerRequest() {
            console.log('register');
            console.log(this.patient);
            axios
                .post('/patient', this.patient)
                .then(response => {
                    if (response.data.success) this.redirectPage(response.data.patientId);
                })
                .catch(err => {
                    alert('Register failed.');
                    console.log(err);
                });
        },
        updateRequest() {
            console.log('update');
            console.log(this.patient);
            axios
                .put('/patient', this.patient)
                .then(response => {
                    if (response.data.success) this.redirectPage();
                })
                .catch(err => {
                    alert('Register failed.');
                    console.log(err);
                });
        },
        isNewInsuranceId() {
            return this.init.insurances.find(
                    insurance => insurance.policyNumber.toString() === this.patient.medical.insuranceId.toString()
                ) === undefined ?
                true :
                false;
        },
        processPatient(patient) {
            this.flagSearch = true;
            this.database.patient.id = patient.id;
            this.database.patient.name.first = patient.firstName;
            this.database.patient.name.last = patient.lastName;
            this.database.patient.dateOfBirth = patient.dob.split('T')[0];
            this.database.patient.gender = patient.gender;
            this.database.patient.email = patient.email;
            this.database.patient.phoneNumber = patient.phoneNumber;
            this.database.patient.identificationNumber = patient.identificationNumber;
            this.database.patient.address.detail = patient.detailAddress;
            this.database.patient.address.area = patient.area;
            this.database.patient.address.postcode = patient.postcode;
            this.database.patient.address.state = patient.state;
            this.database.patient.address.country = patient.country;
            this.database.patient.medical.bloodType = patient.bloodType;
            this.database.patient.medical.bloodRhesus = patient.bloodRhesus;
            this.database.patient.medical.organDonate = patient.organDonate;
            this.database.patient.medical.nextOfKin.name = patient.nextOfKinName;
            this.database.patient.medical.nextOfKin.relationship = patient.nextOfKinRelationship;
            this.database.patient.medical.nextOfKin.phoneNumberHome = patient.nextOfKinPhoneNumberHome;
            this.database.patient.medical.nextOfKin.phoneNumberMobile = patient.nextOfKinPhoneNumberMobile;

            this.patient = {...this.database.patient };
        },
        redirectPage(patientId) {
            let url = '/his/patient/view/queue.html';
            // new patient
            url = patientId ?
                `${url}?patient_id=${patientId}&type=new&insurance_id=${this.patient.medical.insuranceId}` // old patient
                :
                `${url}?patient_id=${this.patient.id}&type=old&insurance_id=${this.patient.medical.insuranceId}`;
            window.location.href = url;
        },
        getInsurances() {
            axios
                .get(`/insurance?patient_id=${this.patient.id}`)
                .then(response => {
                    this.init.insurances = response.data.rst;
                })
                .catch(err => console.log(err));
        },
        checkUniquePolicyNumber() {
            if (this.isNewInsuranceId() && !this.isEmpty(this.patient.medical.insuranceId))
                axios
                .get(`/insurance?policy_number=${this.patient.medical.insuranceId}`)
                .then(response => (this.flagUniqueInsuranceId = response.data.unique))
                .catch(err => console.log(err));
            else this.flagUniqueInsuranceId = true;
        },
        checkUniquePhoneNumber() {
            if (this.isNewPhoneNumber) {
                if (!this.isEmpty(this.patient.phoneNumber)) {
                    this.getPatientRequest('patient_phone_number', this.patient.phoneNumber, (err, result) => {
                        if (err) return console.log(err);
                        this.flagUniquePhoneNumber = result.data.unique;
                    });
                }
            } else this.flagUniquePhoneNumber = true;
        },
        checkUniqueEmail() {
            if (this.isNewEmail) {
                if (!this.isEmpty(this.patient.email))
                    this.getPatientRequest('patient_email', this.patient.email, (err, result) => {
                        if (err) return console.log(err);
                        this.flagUniqueEmail = result.data.unique;
                    });
            } else this.flagUniqueEmail = true;
        },
        checkUniqueIdentificationNumber() {
            if (this.isNewIdentificationNumber && !this.isEmpty(this.patient.identificationNumber))
                this.getPatientRequest('patient_identification_number', this.patient.identificationNumber, (err, result) => {
                    if (err) return console.log(err);
                    this.flagUniqueIdentificationNumber = result.data.unique;
                });
            this.flagUniqueIdentificationNumber = true;
        },
        getInvalidKeys() {
            let validations = this.$v;
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
        isEmpty() {
            return val => val.toString().trim().length === 0 || val.toString().trim() === null || val === undefined;
        },
        isEmail() {
            let reg =
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return val => reg.test(val);
        },
        isInLength() {
            return (val, length) => val.toString().length === length;
        },
        getIsInLengthErrorMessage() {
            return (prop, length, metric) => {
                if (length > metric)
                    return `Please shorthen ${prop} to ${metric} characters (you're currently using ${length} characters)`;

                if (length < metric)
                    return `Please lengthen ${prop} to ${metric} characters (you're currently using ${length} characters)`;
            };
        },
        isNewPhoneNumber() {
            return this.database.patient.phoneNumber.toString() !== this.patient.phoneNumber.toString();
        },
        isNewIdentificationNumber() {
            return this.database.patient.identificationNumber.toString() !== this.patient.identificationNumber.toString();
        },
        isNewEmail() {
            return this.database.patient.email.toString() !== this.patient.email.toString();
        },
        $v: function() {
            let v = this.validations;
            return {
                firstName: v.name.first,
                lastName: v.name.last,
                dateOfBirth: v.dateOfBirth,
                gender: v.gender,
                email: v.email.unique && v.email.required && v.email.isEmail,
                phoneNumber: v.phoneNumber.required && v.phoneNumber.unique,
                identificationNumber: v.identificationNumber.unique && v.identificationNumber.required,
                detailAddress: v.address.detail,
                area: v.address.area,
                postcode: v.address.postcode,
                state: v.address.state,
                country: v.address.country,
                bloodType: v.medical.bloodType,
                bloodRhesus: v.medical.bloodRhesus,
                organDonate: v.medical.organDonate,
                insuranceId: v.medical.insuranceId,
                nextOfKinName: v.medical.nextOfKin.name,
                nextOfKinRelationship: v.medical.nextOfKin.relationship,
                nextOfKinPhoneNumberHome: v.medical.nextOfKin.phoneNumberHome,
                nextOfKinPhoneNumberMobile: v.medical.nextOfKin.phoneNumberMobile,
            };
        },
        validations() {
            return {
                name: {
                    first: !this.isEmpty(this.patient.name.first),
                    last: !this.isEmpty(this.patient.name.last),
                },
                dateOfBirth: !this.isEmpty(this.patient.dateOfBirth),
                gender: !this.isEmpty(this.patient.gender),
                email: {
                    required: !this.isEmpty(this.patient.email),
                    isEmail: this.isEmail(this.patient.email),
                    unique: this.flagUniqueEmail,
                },
                phoneNumber: {
                    required: !this.isEmpty(this.patient.phoneNumber),
                    unique: this.flagUniquePhoneNumber,
                },
                identificationNumber: {
                    required: !this.isEmpty(this.patient.identificationNumber),
                    unique: this.flagUniqueIdentificationNumber,
                },
                address: {
                    detail: !this.isEmpty(this.patient.address.detail),
                    state: !this.isEmpty(this.patient.address.state),
                    area: !this.isEmpty(this.patient.address.area),
                    postcode: !this.isEmpty(this.patient.address.postcode),
                    country: !this.isEmpty(this.patient.address.country),
                },
                medical: {
                    bloodType: !this.isEmpty(this.patient.medical.bloodType),
                    bloodRhesus: !this.isEmpty(this.patient.medical.bloodRhesus),
                    organDonate: !this.isEmpty(this.patient.medical.organDonate),
                    insuranceId: this.isEmpty(this.patient.medical.insuranceId) ? true : this.flagUniqueInsuranceId,
                    nextOfKin: {
                        name: !this.isEmpty(this.patient.medical.nextOfKin.name),
                        relationship: !this.isEmpty(this.patient.medical.nextOfKin.relationship),
                        phoneNumberHome: !this.isEmpty(this.patient.medical.nextOfKin.phoneNumberHome),
                        phoneNumberMobile: !this.isEmpty(this.patient.medical.nextOfKin.phoneNumberMobile),
                    },
                },
            };
        },
    },
    watch: {
        'patient.name.first': function() {
            if (!this.dirty.patient.name.first) this.dirty.patient.name.first = true;
        },
        'patient.name.last': function() {
            if (!this.dirty.patient.name.last) this.dirty.patient.name.last = true;
        },
        'patient.dateOfBirth': function() {
            if (!this.dirty.patient.dateOfBirth) this.dirty.patient.dateOfBirth = true;
        },
        'patient.gender': function() {
            if (!this.dirty.patient.gender) this.dirty.patient.gender = true;
        },
        'patient.email': function() {
            if (!this.dirty.patient.email) this.dirty.patient.email = true;
        },
        'patient.phoneNumber': function() {
            if (!this.dirty.patient.phoneNumber) this.dirty.patient.phoneNumber = true;
        },
        'patient.identificationNumber': function() {
            if (!this.dirty.patient.identificationNumber) this.dirty.patient.identificationNumber = true;
        },
        'patient.address.detail': function() {
            if (!this.dirty.patient.address.detail) this.dirty.patient.address.detail = true;
        },
        'patient.address.state': function() {
            if (!this.dirty.patient.address.state) this.dirty.patient.address.state = true;
        },
        'patient.address.area': function() {
            if (!this.dirty.patient.address.area) this.dirty.patient.address.area = true;
        },
        'patient.address.postcode': function() {
            if (!this.dirty.patient.address.postcode) this.dirty.patient.address.postcode = true;
        },
        'patient.address.country': function() {
            if (!this.dirty.patient.address.country) this.dirty.patient.address.country = true;
        },
        'patient.medical.bloodType': function() {
            if (!this.dirty.patient.medical.bloodType) this.dirty.patient.medical.bloodType = true;
        },
        'patient.medical.bloodRhesus': function() {
            if (!this.dirty.patient.medical.bloodRhesus) this.dirty.patient.medical.bloodRhesus = true;
        },
        'patient.medical.organDonate': function() {
            if (!this.dirty.patient.medical.organDonate) this.dirty.patient.medical.organDonate = true;
        },
        'patient.medical.insuranceId': function() {
            if (!this.dirty.patient.medical.insuranceId) this.dirty.patient.medical.insuranceId = true;
        },
        'patient.medical.nextOfKin.name': function() {
            if (!this.dirty.patient.medical.nextOfKin.name) this.dirty.patient.medical.nextOfKin.name = true;
        },
        'patient.medical.nextOfKin.relationship': function() {
            if (!this.dirty.patient.medical.nextOfKin.relationship) this.dirty.patient.medical.nextOfKin.relationship = true;
        },
        'patient.medical.nextOfKin.phoneNumberHome': function() {
            if (!this.dirty.patient.medical.nextOfKin.phoneNumberHome)
                this.dirty.patient.medical.nextOfKin.phoneNumberHome = true;
        },
        'patient.medical.nextOfKin.phoneNumberMobile': function() {
            if (!this.dirty.patient.medical.nextOfKin.phoneNumberMobile)
                this.dirty.patient.medical.nextOfKin.phoneNumberMobile = true;
        },
    },
});