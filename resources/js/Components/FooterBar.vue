<script setup>
import { computed } from 'vue'
import { containerMaxW } from '@/config.js';
import BaseLevel from '@/Components/BaseLevel.vue'

const year = computed(() => new Date().getFullYear())
</script>

<template>
  <footer class="py-2 px-6">
    <BaseLevel :class="containerMaxW">
      <div class="text-center md:text-left">
        <b>&copy;{{ year }}, <a href="https://www.uncletoms.com" target="_blank" rel="noopener noreferrer">Uncle
            Tom's</a>.</b>
        all rights reserved
      </div>
      <div class="md:py-2">
        <a href="/version">
          Version 1.0
        </a>
      </div>
    </BaseLevel>
  </footer>
</template>