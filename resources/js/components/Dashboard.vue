<template>
  <div style="max-width:900px;margin:auto">
    <h2>User Management</h2>

    <table border="1" cellpadding="10" width="100%">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th v-if="isAdmin">Password (for new users)</th>
        </tr>
      </thead>

      <tbody>

        
        <tr v-for="user in users" :key="'u'+user.id">
          <td>
              <input type="hidden" :value="user.id">
              <input v-model="user.name" :disabled="!isAdmin" @input=" markDirty(user)">
          </td>
          <td>
              <input v-model="user.email" :disabled="!isAdmin" @input="markDirty(user)">
          </td>
          <td>
            <div v-if="!user.id">
              <input 
                v-model="user.password" 
                type="password" 
                placeholder="Password"
              >
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Controls -->
    <div v-if="isAdmin" style="margin-top:15px;display:flex;gap:10px;justify-content:flex-end">
      <button @click="addRow">+ Add New User</button>
      <button @click="saveAll" :disabled="!hasDirtyRows">Save All</button>
    </div>

  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      users: [],
      isAdmin: false,
      savedRows: {},// Track which rows have been recently saved
      password: ''
    }
  },


  computed: {
    hasDirtyRows() {
      return this.users.some(u => u.isDirty)
    }
  },

  mounted() {
    this.loadUsers()
  },

  methods: {

    async loadUsers() {
      const res = await axios.get('/api/users')
      this.users = res.data.users
      this.users = res.data.users.map(user => ({
        ...user,
        password: '',
        isDirty: false
      }))

      this.isAdmin = res.data.is_admin

    },

    addRow() {
      this.users.push({
        name: '',
        email: '',
        password: '',
        isDirty: true
      })
    },

    markDirty(user) {
      user.isDirty = true
    },

    // removeNew(index) {
    //   this.users.splice(index,1)
    // },

    async saveAll() {
      const dirtyUsers = this.users.filter(u => u.isDirty)

      if (!dirtyUsers.length) {
        alert('No changes to save')
        return
      }

      await axios.post('/api/users/bulk', {
        users: dirtyUsers
      })

      this.loadUsers()
      alert('Users saved successfully')
    }

  }
}
</script>
