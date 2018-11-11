<template>
  <div class="hello">
    <h1>Transactions</h1>
    <div id="people">
      <v-server-table
        ref="bTransactions"
        :options="options"
        :columns="columns"
        url="/app"
        name="braintreeTransactions"
      >
        <a slot="transactionID" slot-scope="props" :href="edit(props.row.transactionID)"><i class="fa fa-eye" aria-hidden="true"/> {{ props.row.transactionID }}</a>
      </v-server-table>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import moment from "moment"

export default {
  name: 'HelloWorld',
  data: () => ({
    columns: ['transactionID', 'BillingFirstNames', 'BillingSurname', 'Amount', 'pay_for', 'date', 'Status', 'user_id'],
    options: {
      preserveState: true,
      serverMultiSorting: true,
      filterByColumn: true,
      storage: 'session',
      headings: {
        transactionID: 'Transaction Id',
        BillingFirstNames: 'Firstname',
        BillingSurname: 'Surname',
        Amount: 'Amount',
        pay_for: 'Pay For',
        date: 'Date',
        user_id: 'User Id',
      },
      orderBy:{
        column: 'date',
        ascending:false,
      },
      perPage: 100,
      highlightMatches: true,
      params: {
        start: '2018-10-08',
        end: '2018-10-08'
      },
      initFilters:{
        GENERIC: 'authorized'
      }
    }
  }),
  computed:{
    ...mapGetters({
      // transactions: 'transactions'
    })
  },
  created(){
    // this.getTransactions()
    // console.log(this.$refs.bTransactions.filteredData)
  },
  methods:{
    ...mapActions({
      getTransactions: 'getTransactions'
    }),
    edit(id){
      console.log(id)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
