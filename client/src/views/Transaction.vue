<template>
  <div class="transaction">
    <h1>
      Portal Braintree Transaction
      <small>{{ $route.params.transactionId }}</small>
      <small
        v-if="latestHistoryRecord.completed && transaction.status !== 'authorized' && latestHistoryRecord.electraRef"
        class="pull-right completed"
      >
        <i class="fa fa-check fa-fw" aria-hidden="true"/>Completed
      </small>
    </h1>
    <v-wait for="braintree.transaction">
      <template slot="waiting"><loading/></template>
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-primary">
            <h3 class="panel-heading v-panel-heading">Customer information</h3>
            <div class="panel-body">
              <div class="table-responsive">
                <table v-if="Object.keys(latestHistoryRecord).length !== 0" class="table table-striped v-table">
                  <tr>
                    <td>Electra Ref</td>
                    <td>
                      <strong v-if="latestHistoryRecord.electraRef">{{ latestHistoryRecord.electraRef }}</strong>
                      <div
                        v-else
                        :class="{
                          'has-error': $v.settleForm.electraRef.$invalid && $v.settleForm.electraRef.$dirty,
                          'has-success': !$v.settleForm.electraRef.$invalid
                        }"
                        class="form-group"
                      >
                        <input
                          id="electraRef"
                          v-model="settleForm.electraRef"
                          type="text"
                          class="form-control"
                          placeholder="Enter Electra Reference"
                          @input="$v.settleForm.electraRef.$touch()"
                        >
                        <div v-if="$v.settleForm.electraRef.$invalid && $v.settleForm.electraRef.$dirty">
                          <span v-if="!$v.settleForm.electraRef.integer" id="error" class="help-block">Electra refrerence must be numbers only</span>
                          <span v-if="!$v.settleForm.electraRef.required" id="error" class="help-block">Electra refrerence is required</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Firstname</td>
                    <td><strong>{{ latestHistoryRecord.billing.firstname }}</strong></td>
                  </tr>
                  <tr>
                    <td>Surname</td>
                    <td><strong>{{ latestHistoryRecord.billing.surname }}</strong></td>
                  </tr>
                  <tr>
                    <td>Billing Address</td>
                    <td>
                      <strong>
                        <address>
                          {{ latestHistoryRecord.billing.address.line1 }}<br>
                          {{ latestHistoryRecord.billing.address.city }}<br>
                          {{ latestHistoryRecord.billing.address.postcode }}
                        </address>
                      </strong>
                    </td>
                  </tr>
                  <tr>
                    <td>Phone</td>
                    <td><strong>{{ latestHistoryRecord.phone }}</strong></td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td><strong>{{ latestHistoryRecord.email }}</strong></td>
                  </tr>
                  <tr>
                    <td>Vehicle Reg </td>
                    <td><strong>{{ latestHistoryRecord.vehicleReg }}</strong></td>
                  </tr>
                  <tr>
                    <td>Additional Information</td>
                    <td><strong>{{ latestHistoryRecord.additionalInformation }}</strong></td>
                  </tr>
                </table>
              </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
          </div><!-- /.panel -->
        </div><!-- /.col-md-6 -->
        <div class="col-md-6">
          <div v-if="Object.keys(transaction).length !== 0" class="panel panel-default">
            <h3 class="panel-heading v-panel-heading">Payment information</h3>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped v-table">
                  <tr>
                    <td>Transaction Id</td>
                    <td><strong>{{ transaction.id }}</strong></td>
                  </tr>
                  <tr>
                    <td>Amount</td>
                    <td><strong>{{ transaction.amount }} <small>{{ transaction.currency }}</small></strong></td>
                  </tr>
                  <template v-if="transaction.cardType">
                    <tr>
                      <td>Card Type</td>
                      <td><img :src="transaction.cardImageUrl" :alt="transaction.cardType"></td>
                    </tr>
                    <tr>
                      <td>Last 4</td>
                      <td><strong>{{ transaction.last4 }}</strong></td>
                    </tr>
                    <tr>
                      <td>Card Holder Name</td>
                      <td><strong>{{ transaction.cardholderName }}</strong></td>
                    </tr>
                    <tr>
                      <td>Pay For</td>
                      <td><strong>{{ transaction.payFor }}</strong></td>
                    </tr>
                  </template>
                  <template v-else>
                    <tr>
                      <td>Payment Type</td>
                      <td><img :src="transaction.paypalImageUrl" alt="Paypal"></td>
                    </tr>
                    <tr>
                      <td>Paypal Email</td>
                      <td><strong>{{ transaction.paypalEmail }}</strong></td>
                    </tr>
                    <tr>
                      <td>Payer name</td>
                      <td><strong>{{ transaction.paypalFirstname }} {{ transaction.paypalSurname }} </strong></td>
                    </tr>
                    <tr>
                      <td>Pay For</td>
                      <td><strong>{{ transaction.paypalDescription }}</strong></td>
                    </tr>
                  </template>
                  <tr>
                    <td>Current Status</td>
                    <td>
                      <strong
                        :class="{
                          'ai-danger': transaction.status === 'settlement_declined'
                        }"
                      >
                        {{ transaction.status }}
                      </strong>
                    </td>
                  </tr>
                  <tr v-if="transaction.additionalProcessorResponse">
                    <td>Additional Processor Response</td>
                    <td><strong>{{ transaction.additionalProcessorResponse }}</strong></td>
                  </tr>
                  <tr>
                    <td>Created At</td>
                    <td><strong>{{ transaction.createdAt }}</strong></td>
                  </tr>
                  <tr>
                    <td>Updated At</td>
                    <td><strong>{{ transaction.updatedAt }}</strong></td>
                  </tr>
                </table>
              </div>
            </div>
          </div><!-- /.panel -->
        </div><!-- /.col-md-6 -->
      </div><!-- /.row -->
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <a class="btn btn-default" @click="$router.go(-1)"><i class="fa fa-undo fa-fw" aria-hidden="true"/>Back to Transactions</a>
              <div class="btn-group pull-right">
                <v-button
                  v-if="transaction.status === 'authorized'"
                  :loading="settleForm.busy"
                  :disabled="$v.settleForm.$invalid"
                  type="button"
                  variant="primary"
                  @click.native="settle()"
                >
                  <i class="fa fa-university fa-fw" aria-hidden="true"/>Submit for settlement
                </v-button>
                <v-button
                  v-if="!latestHistoryRecord.completed && transaction.status !== 'authorized'"
                  :loading="settleForm.busy"
                  :disabled="$v.settleForm.$invalid"
                  type="button"
                  variant="warning"
                  @click.native="complete()"
                >
                  <i class="fa fa-check fa-fw" aria-hidden="true"/>Mark as complete
                </v-button>
              </div>
            </div>
          </div>
        </div><!-- /.col-md-12 -->
      </div><!-- /.row -->
    </v-wait>
    <v-wait for="braintree.transactionHistory">
      <template slot="waiting"><loading/></template>
      <h2>Transaction History</h2>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-striped v-table">
            <tr>
              <th>Electra Ref</th>
              <th>Date</th>
              <th>Time</th>
              <th>Transaction Id</th>
              <th>Payment Type</th>
              <th>Billing Name</th>
              <th>Amount</th>
              <th>User</th>
              <th>Status</th>
              <th>Vendor</th>
            </tr>
            <tr v-for="transaction in transactionHistory" :key="transaction.id" >
              <td>
                <div v-if="transaction.electraRef">{{ transaction.electraRef }}</div>
                <div v-else class="ai-danger">missing</div>
              </td>
              <td>{{ transaction.date }}</td>
              <td>{{ transaction.time }}</td>
              <td>{{ transaction.transactionId }}</td>
              <td><img :src="transaction.imageUrl" :alt="transaction.cardType"></td>
              <td>{{ transaction.billing.firstname }} {{ transaction.billing.surname }}</td>
              <td>{{ transaction.amount }} <small>{{ transaction.currency }}</small></td>
              <td>{{ transaction.userId }}</td>
              <td>{{ transaction.status }}</td>
              <td>{{ transaction.vendor }}</td>
            </tr>
          </table>
        </div>
      </div><!-- /.row -->
    </v-wait>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Form from "vform";
import { required, integer } from "vuelidate/lib/validators";

export default {
  name: "Transaction",
  data: () => ({
    settleForm: new Form({
      electraRef: null,
      transactionId: null
    }),
    success: false
  }),
  validations: {
    settleForm: {
      electraRef: {
        required,
        integer
      }
    }
  },
  computed: {
    ...mapGetters({
      transaction: "braintree/transaction",
      transactionHistory: "braintree/transactionHistory"
    }),
    latestHistoryRecord: function() {
      let maxid = 0;
      this.transactionHistory.map(function(transaction) {
        if (transaction.id > maxid) maxid = transaction.id;
      });

      let trans = this.transactionHistory.filter(function(transaction) {
        return transaction.id === maxid;
      });

      if (trans.length === 1) {
        return trans[0];
      } else {
        return {};
      }
    }
  },
  async mounted() {
    await this.getTransactionHistory(this.$route.params.transactionId);
    await this.getTransaction(this.$route.params.transactionId);
    if (this.latestHistoryRecord.electraRef) {
      this.settleForm.electraRef = this.latestHistoryRecord.electraRef;
    }
  },
  methods: {
    ...mapActions({
      getTransaction: "braintree/getTransaction",
      getTransactionHistory: "braintree/getTransactionHistory",
      setTransSettlement: "braintree/setTransactionSettlement"
    }),
    async settle() {
      this.settleForm.transactionId = this.transaction.id;
      try {
        const { value } = await this.$swal({
          title: "Are you sure?",
          text: `Amount: £${this.transaction.amount}, Electra Reference: ${
            this.settleForm.electraRef
          }`,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Submit!"
        });

        if (value) {
          const { data } = await this.settleForm.post(
            "/app/braintree/submit-for-settlement"
          );
          this.success = data.success;
          this.setTransSettlement({
            transaction: data.data.transaction,
            history: data.data.transactionHistory
          });

          this.$swal({
            title: "Transaction",
            text: data.message,
            type: this.success ? "success" : "error",
            confirmButtonText: "OK"
          });
        }
      } catch (e) {
        console.error(e);
      }
    },
    async complete() {
      try {
        this.settleForm.transactionId = this.transaction.id;
        const { data } = await this.settleForm.post(
          "/app/braintree/mark-complete"
        );
        this.success = data.success;
        await this.getTransactionHistory(this.transaction.id);
      } catch (e) {
        this.$swal(
          "Error",
          "Transaction can't be completed without a valid braintree transaction! Please contact a member of the dev team",
          "error"
        );
        console.error(e.response);
      }
    }
  }
};
</script>
<style scoped lang="scss">
.v-table {
  td {
    padding: 10px 0;
    font-size: 16px;
    text-align: left;
    vertical-align: middle;
  }
}

.v-panel-heading {
  margin-top: 0;
}

.completed {
  color: #679a01;
}

.enter {
  transform: translateX(100%);
}
.enter-to {
  transform: translateX(0);
}
.slide-enter-active {
  position: absolute;
}

.leave {
  transform: translateX(0);
}
.leave-to {
  transform: translateX(-100%);
}

.slide-enter-active,
.slide-leave-active {
  transition: all 750ms ease-in-out;
}

.history {
  backface-visibility: hidden;
  z-index: 1;
}

/* moving */
.history-move {
  transition: all 600ms ease-in-out 50ms;
}

/* appearing */
.history-enter-active {
  transition: all 400ms ease-out;
}

/* disappearing */
.history-leave-active {
  transition: all 200ms ease-in;
  position: absolute;
  z-index: 0;
}

/* appear at / disappear to */
.history-enter,
.history-leave-to {
  opacity: 0;
}
</style>
