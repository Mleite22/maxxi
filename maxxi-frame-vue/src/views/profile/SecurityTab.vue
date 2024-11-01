<script setup lang="ts">
// import { ref } from 'vue';
import { ref, computed } from 'vue';

const password = ref('');
const newPassword = ref('');
const confirmNewPassword = ref('');

// UI state references
const isLoading = ref(false);
const emailSent = ref(false);
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');
const snackbarIcon = ref('');


function showSnackbar(color: string, message: string, icon: string) {
  snackbarColor.value = color;
  snackbarMessage.value = message;
  snackbar.value = true;
  snackbarIcon.value = icon
}


async function submit() {
  isLoading.value = true;

  // Validação dos campos antes da chamada da API
  if (!password.value || !newPassword.value || !confirmNewPassword.value) {
    showSnackbar('error', 'Todos os campos são obrigatórios', 'error');
    isLoading.value = false;
    return;
  }
  if (newPassword.value !== confirmNewPassword.value) {
    showSnackbar('error', 'As novas senhas não correspondem', 'error');
    isLoading.value = false;
    return;
  }

  try {
    // Chamada da API para redefinir a senha
    const response = await fetch('/api/password-recover/recover', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        password: password.value,
        newPassword: newPassword.value,
        confirmNewPassword: confirmNewPassword.value,
      }),
    });

    // Verifique o status da resposta
    if (!response.ok) {
      // Se a resposta não for 2xx, trate como um erro
      const errorData = await response.json();
      showSnackbar('error', errorData.message || 'Erro ao redefinir a senha', 'error');
    } else {
      const data = await response.json();
      if (data.success) {
        showSnackbar('success', 'Senha redefinida com sucesso', 'check_circle');
      } else {
        showSnackbar('error', 'Erro ao redefinir a senha', 'error');
      }
    }
  } catch (error) {
    console.error(error);
    showSnackbar('error', 'Erro ao redefinir a senha', 'error');
  } finally {
    // Garanta que o estado de carregamento é atualizado
    isLoading.value = false;
  }
}

</script>

<template>
  <v-form>
    <v-row>
      <v-col cols="12" lg="6">
        <!-- <v-label class="mb-2">Current Password</v-label> -->
        <v-label class="mb-2">Senha Atual</v-label>
        <v-text-field type="password" density="comfortable" single-line v-model="password"
          placeholder="Enter Current Password" variant="outlined">
        </v-text-field>
      </v-col>
    </v-row>
    <v-row class="mt-3">
      <v-col cols="12" lg="6">
        <v-label class="mb-2">New Password</v-label>
        <v-text-field type="password" density="comfortable" single-line v-model="newPassword"
          placeholder="Enter New Password" variant="outlined">
        </v-text-field>
      </v-col>
      <v-col cols="12" lg="6">
        <v-label class="mb-2">Confirm Password</v-label>
        <v-text-field type="password" density="comfortable" single-line v-model="confirmNewPassword"
          placeholder="Enter Confirm Password" variant="outlined">
        </v-text-field>
      </v-col>
    </v-row>
    <div class="text-end mt-4">
      <v-divider />
      <!-- <v-btn variant="flat" color="primary" rounded="md" class="mt-4" :disabled="!isFormValid" @click="submit">Reset Password</v-btn> -->
      <v-btn color="primary" rounded="md" variant="flat" class="mt-4" :loading="isLoading" @click="submit">
        Continue
      </v-btn>
    </div>
  </v-form>
</template>
