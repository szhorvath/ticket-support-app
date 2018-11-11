import * as types from "../mutation-types"
import axios from "axios"
import swal from "sweetalert2"

// state
export const state = {
  transaction: {},
  transactionHistory: []
}

// getters
export const getters = {
  transaction: state => state.transaction,
  transactionHistory: state => state.transactionHistory
}

// mutations
export const mutations = {
  [types.SET_TRANSACTION](state, { transaction }) {
    state.transaction = transaction
  },

  [types.SET_TRANSACTION_HISTORY](state, { history }) {
    state.transactionHistory = history
  },

  [types.SET_TRANSACTION_SETTLEMENT](state, { transaction, history }) {
    state.transactionHistory.push(history)
    state.transaction = transaction
  }
}

// actions
export const actions = {
  async getTransaction({ commit, dispatch }, transactionId) {
    try {
      dispatch("wait/start", "braintree.transaction", { root: true })
      const { data } = await axios.get(
        `/app/braintree/transaction/${transactionId}`
      )

      dispatch("wait/end", "braintree.transaction", { root: true })

      if (!data.success) {
        const result = await swal(
          "Transaction Not Found",
          data.errors.transactionId[0],
          "error"
        )
        if (result.value) {
          data.data = {}
        }
      }

      commit(types.SET_TRANSACTION, { transaction: data.data })
    } catch (e) {
      dispatch("wait/end", "braintree.transaction", { root: true })
      // console.error(e)
    }
  },

  async getTransactionHistory({ commit, dispatch }, transactionId) {
    try {
      dispatch("wait/start", "braintree.transactionHistory", { root: true })
      const { data } = await axios.get(
        `/app/braintree/transaction-history/${transactionId}`
      )
      dispatch("wait/end", "braintree.transactionHistory", { root: true })
      commit(types.SET_TRANSACTION_HISTORY, { history: data.data })
    } catch (e) {
      dispatch("wait/end", "braintree.transactionHistory", { root: true })
      // console.error(e)
    }
  },

  async setTransactionSettlement({ commit }, { transaction, history }) {
    try {
      commit(types.SET_TRANSACTION_SETTLEMENT, {
        transaction: transaction,
        history: history
      })
    } catch (e) {
      console.error(e)
    }
  }
}
