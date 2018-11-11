<template>
  <div class="renewal-transactions">
    <h1>Renewal Transactions <small>Motor</small></h1>
    <div id="transactions-table">
      <rangedate-picker :init-range="initRange" i18n="EN" @selected="onDateSelected"/>
      <v-server-table
        ref="bTransactions"
        :options="options"
        :columns="columns"
        url="/app/braintree/transactions/Motor"
        name="braintreeRenewalMotorTransactions"
      >
        <template slot="completed" slot-scope="props" >
          <div class="completed">
            <i v-if="props.row.completed" class="fa fa-check" aria-hidden="true"/>
            <i v-if="!props.row.completed" class="fa fa-times" aria-hidden="true"/>
          </div>
        </template>
        <router-link
          slot="transactionId"
          slot-scope="props"
          :to="{
            name: 'transaction',
            params: {
              transactionId: props.row.transactionId
            }
          }"
        >
          <i class="fa fa-eye" aria-hidden="true"/> {{ props.row.transactionId }}
        </router-link>
        <template slot="status" slot-scope="props">
          <span :class="{'ai-danger': props.row.status === 'settlement_declined'}">{{ props.row.status }}</span>
        </template>
        <template slot="firstname" slot-scope="props"> {{ props.row.billing.firstname }}</template>
        <template slot="surname" slot-scope="props"> {{ props.row.billing.surname }}</template>
      </v-server-table>
    </div>
  </div>
</template>
<script>
import { subMonths, addDays, subDays } from 'date-fns'

const endDate =  addDays(new Date(), 1)
const  startDate = subMonths(endDate, 1)

export default {
  name: 'RenewalTransactionsHome',
  data: () => ({
    timeout: null,
    columns: ['completed','transactionId', 'status', 'firstname', 'surname', 'amount', 'payFor', 'createdAt', 'userId'],
    options: {
      preserveState: true,
      serverMultiSorting: true,
      filterByColumn: true,
      listColumns: {
        completed: [
          {id: 1, text: 'completed'},
          {id: 0, text: 'incomplete'}
        ]
      },
      storage: 'session',
      debounce: '1000',
      orderBy:{
        column: 'createdAt',
        ascending:false,
      },
      perPage: 25,
      highlightMatches: true,
      params: {
        range:{
            date: {
              start: startDate.toISOString(),
              end: endDate.toISOString()
            }
          }
      },
      initFilters:{
        completed: 0
      }
    }
  }),
  computed: {
    initRange: function(){
        return {
          start: startDate,
          end: endDate
        }
    }
  },
  mounted(){
    this.refreshPeriodically()
  },
  destroyed() {
    clearTimeout(this.timeout);
  },
  methods:{
    onDateSelected(daterange){
      const start = subDays(daterange.start, 1)
      const end = subDays(daterange.end, 1)
      this.options.params.range.date = {
        start: start.toISOString(),
        end: end.toISOString(),
      }
      this.$refs.bTransactions.refresh()
    },
    async refreshPeriodically() {
      await this.$refs.bTransactions.refresh()
      this.timeout = setTimeout(() => {
          this.refreshPeriodically(false);
      }, 30000);
    }
  }
}
</script>
<style lang="scss" scoped>
.completed {
  text-align: center;

  .fa {
    font-size: 16px;
  }

  .fa-check {
    color: #7cb440;
  }
  .fa-times {
    color: #a42625;
  }
}
</style>