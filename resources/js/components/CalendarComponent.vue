<template>
    <div class="container-fluid">
        <div class="mt-2 alert text-center alert-custom"
             v-if="alert.show"
             :class="'alert-' + alert.type"
             role="alert"
             v-html="alert.message"></div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header text-center font-weight-bold">Create Event</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="eventName">Event Name</label>
                                <input type="email" class="form-control" id="eventName"
                                       v-model="eventName"
                                       placeholder="Event Name">
                            </div>
                            <div class="form-group">
                                <label>Select Date Range</label>
                                <vc-date-picker
                                    id="dateRange"
                                    class="text-center w-100"
                                    is-range
                                    v-model="dateRange"
                                ></vc-date-picker>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="1" v-model="checkedDay"
                                       id="Monday">
                                <label class="form-check-label" for="Monday">Monday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="2" v-model="checkedDay"
                                       id="Tuesday">
                                <label class="form-check-label" for="Tuesday">Tuesday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="3" v-model="checkedDay"
                                       id="Wednesday">
                                <label class="form-check-label" for="Wednesday">Wednesday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="4" v-model="checkedDay"
                                       id="Thursday">
                                <label class="form-check-label" for="Thursday">Thursday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="5" v-model="checkedDay"
                                       id="Friday">
                                <label class="form-check-label" for="Friday">Friday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="6" v-model="checkedDay"
                                       id="Saturday">
                                <label class="form-check-label" for="Saturday">Saturday</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="0" v-model="checkedDay"
                                       id="Sunday">
                                <label class="form-check-label" for="Sunday">Sunday</label>
                            </div>
                            <div class="text-center w-100">
                                <button type="button" :disabled="isSaving" @click="validateForm"
                                        class="btn btn-primary mt-2 center-block">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <vc-calendar
                            class="custom-calendar max-w-full"
                            :masks="calendarMasks"
                            :attributes="attributes"
                            disable-page-swipe
                            is-expanded
                        >
                            <template v-slot:day-content="{ day, attributes }">
                                <div class="flex flex-col h-full z-10 overflow-hidden">
                                    <span class="day-label text-sm text-gray-900">{{ day.day }}</span>
                                    <div class="flex-grow overflow-y-auto overflow-x-auto event-box-container">
                                        <p
                                            v-for="attr in attributes"
                                            class="text-xs leading-tight rounded-sm p-1 mt-0 mb-1 event-box"
                                            :style="{ background: attr.customData.background }"
                                            :title = "attr.customData.title"
                                        >
                                            {{ attr.customData.title }}
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </vc-calendar>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                eventName: null,
                dateRange: null,
                checkedDay: [],
                eventsList: [],
                formDataParam: null,
                calendarMasks: {
                    weekdays: 'WWW',
                },
                isSaving: false,
                alertTo: null,
                colorCode: {},
                attributes: [],
                alert: {
                    show: false,
                    type: 'primary',
                    message: 'Test Message!'
                }
            };
        },

        mounted() {
            this.getEvents()
        },

        methods: {
            /**
             * FORM VALIDATION BEFORE SAVING
             */
            validateForm() {
                if (!this.eventName) {
                    this.showAlert('danger', 'Event Name is required')
                    return
                }

                if (this.dateRange === null) {
                    this.showAlert('danger', 'Please select date range')
                    return
                }
                if (this.checkedDay.length < 1) {
                    this.showAlert('danger', 'Please select day/s affected')
                    return
                }

                this.formDataParam = {
                    eventName: this.eventName,
                    start: this.dateRange.start.toLocaleDateString(),
                    end: this.dateRange.end.toLocaleDateString(),
                    checkedDay: this.checkedDay
                }

                const confirmation = confirm("Are you sure you want to create event? \n Event Name: "
                    + this.eventName +
                    "\n Date From: " + this.dateRange.start.toLocaleDateString() +
                    "\n Date To: " + this.dateRange.end.toLocaleDateString())

                if (confirmation === true) {
                    this.saveEvent()
                }
            },

            /**
             * SAVE EVENT TO THE DATABASE
             */
            saveEvent() {
                this.isSaving = true
                this.showAlert('info', 'Saving Event..', false)

                fetch(`/events`, {
                    method: 'POST',
                    body: JSON.stringify(this.formDataParam),
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.isSaving = false
                    if (data.code == 200) {
                        this.getEvents()
                        this.resetForm()
                        this.showAlert('primary', 'Successfully saved!')
                    } else {
                        this.showAlert('danger', data.msg)
                    }
                })
            },

            /**
             * GET EVENTS LIST
             */
            getEvents() {
                fetch(`/events`, {
                    method: 'GET',
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.formatEvents(data.data)
                })
            },

            /**
             * @param data
             * SET EVENTS FORMAT BASED OF VCALENDAR FORMAT
             */
            formatEvents(data) {
                if (data.length > 0) {
                    for (let x = 0; x < data.length; x ++) {

                        let ev = {
                            key: x,
                            customData: {
                                title: data[x].event_name,
                                background: data[x].bgcolor
                            },
                            dates: new Date(data[x].event_date),
                        }

                        this.attributes.push(ev)
                    }
                }
            },

            /**
             * RESET FORM DATA
             */
            resetForm() {
                this.dateRange = null
                this.eventName = null
                this.checkedDay = []
            },

            /**
             * @param type
             * @param message
             * @param autohide
             * SHOW ALERT
             */
            showAlert(type, message, autohide) {
                autohide = autohide === undefined ? true: autohide

                this.alert = {
                    show: true,
                    type: type,
                    message: message
                }

                if (autohide) {
                    if (this.alertTo) clearTimeout(this.alertTo)

                    this.alertTo = setTimeout(() => {
                        this.alert.show = false
                    }, 4000)
                }
            }
        }
    };
</script>