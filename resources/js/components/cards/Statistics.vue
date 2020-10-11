<template>
     <div class="flex flex-col justify-center items-center">
        <div class="group_statistics" v-if="!loading">
            <div class="flex flex-wrap justify-between items-end">
                <div class="flex flex-col">
                    <h1 class="my-8">{{card.bank}} Card</h1>
                    <date-range-picker :value.sync="date_range" placeholder="Date Range" @update:value="getData()"></date-range-picker>
                    <select-box :value.sync="chart_type" placeholder="Chart Type" :options="chart_type_options">
                        <template v-slot:option="{option}">
                            <span :value="option.value">{{option.name}}</span>
                        </template>
                    </select-box>
                </div>
                <ul class="top_info">
                    <li name="total_gain">
                        <img src="/assets/move_money.png" alt="">
                        <label class="text-3xl">Total Gain</label>
                        <b>{{total_gain}} T</b>
                    </li>
                    <li name="total_loss">
                        <img src="/assets/trash_money.png" alt="">
                        <label class="text-3xl">Total Loss</label>
                        <b>{{total_loss}} T</b>
                    </li>
                    <li name="total_transactions">
                        <img src="/assets/card_payment.png" alt="">
                        <label class="text-2xl">Total Transactions</label>
                        <b>{{total_transactions}}</b>
                    </li>
                </ul>
            </div>
            <hr class="my-4">
            <nav class="period">
                <ul>
                    <li :class="{'selected':period=='daily'}" @click="changePeriod('daily')">
                        <span>Daily</span>
                    </li>
                    <li :class="{'selected':period=='monthly'}" @click="changePeriod('monthly')">
                        <span>Monthly</span>
                    </li>
                    <li :class="{'selected':period=='yearly'}" @click="changePeriod('yearly')">
                        <span>Yearly</span>
                    </li>
                </ul>
            </nav>
            <div class="chart">
                <apexchart height="600" :type="chart_type.value" :options="chart_options" :series="chart_series"></apexchart>
            </div>
        </div>
        <loading v-else></loading>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'
    import PopUpDialog from '../layouts/PopUpDialog'
    import SelectBox from '../layouts/form/SelectBox'
    import DateRangePicker from '../layouts/form/DateRangePicker'
    import VueApexCharts from 'vue-apexcharts'

    export default {
        name: 'CardsStatistics',
        components: {
            'loading': Loading,
            'pop-up-dialog': PopUpDialog,
            'select-box': SelectBox,
            'date-range-picker': DateRangePicker,
            'apexchart': VueApexCharts,
        },
        data(){
            return {
                loading: true,
                access_token: null,

                card: {},

                period: 'daily',
                date_range: {},
                chart_type: {value:'area', name:'Area Chart'},
                chart_type_options: [
                    {value:'area', name:'Area Chart'},
                    {value:'bar', name:'Bar Chart'},
                    {value:'line', name:'Line Chart'},
                ],

                // chart: '',
                chart_options: {
                    chart: {},
                    xaxis: {
                        categories: [],
                    },
                },
                chart_series: [
                    {
                        name: 'Gain', color: '#90cacf',
                        data: [],
                    },
                    {
                        name: 'Loss', color: '#ffc4bc',
                        data: [],
                    },
                    {
                        name: 'Total', color: '#ece17e',
                        data: [],
                    },
                ],

                total_gain: 0,
                total_loss: 0,
                total_transactions: 0,
            }
        },
        async created(){
            await token.getToken().then((value)=>{ this.access_token = value; });
            if(this.access_token == null){
                this.$router.push('/login');
            }
            this.loading = false;
        },
        mounted(){
            this.getCard();
            this.getData();
        },
        computed: {
            ...mapGetters(['isLoggedIn']),
        },
        watch: {
            isLoggedIn(newValue,oldValue){
                if(!newValue){
                    this.$router.push('/login');
                }else{ this.loading = false; }
            },
        },
        methods: {
            async getCard(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/cards/${this.$route.params.card_id}`,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.card = response.data;
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            async getData(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                if(this.date_range.from_date){ form_data.append('from_date',this.date_range.from_date); }
                if(this.date_range.to_date){ form_data.append('to_date',this.date_range.to_date); }
                form_data.append('period',this.period);

                this.loading = true;
                axios({
                    url: `/api/v1/cards/${this.$route.params.card_id}/statistics`,
                    data: form_data,
                    method: 'post',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.chart_options = {
                        chart: {},
                        xaxis: {
                            categories: response.data.categories,
                        },
                        yaxis: {
                            labels: {
                                formatter: function (value) {
                                    return value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function (value) {
                                    return value.toLocaleString();
                                }
                            }
                        },
                        dataLabels: {
                            formatter: function (value) {
                                return value.toLocaleString();
                            },
                        }
                    };
                    this.chart_series = [
                        {
                            name: 'Gain', color: '#90cacf',
                            data: response.data.gain,
                        },
                        {
                            name: 'Loss', color: '#ffc4bc',
                            data: response.data.loss,
                        },
                        {
                            name: 'Total', color: '#ece17e',
                            data: response.data.total,
                        },
                    ];

                    this.total_gain = response.data.total_gain.toLocaleString();
                    this.total_loss = response.data.total_loss.toLocaleString();
                    this.total_transactions = response.data.total_transactions.toLocaleString();

                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            changePeriod(newPeriod){
                this.period = newPeriod;
                this.getData();
            },
        },
    }
</script>

<style>

</style>