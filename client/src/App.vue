<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </div>
    <transition name="page" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>

<style lang="scss">
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  margin-top: 60px;
}

.ai-danger {
  color: #a42625;

  &::before {
    content: '\f06a';
    font-family: FontAwesome;
    /*--adjust as necessary--*/
    padding-right: 0.5em;
  }
}

$bouncy: cubic-bezier(0.2, 1.3, 0.7, 1);

.has-error {
  border-color: #a42625;
  animation: shake 500ms $bouncy both;
  transform: translate3d(0, 0, 0);
  backface-visibility: hidden;
  perspective: 1000px;
}

/*--------------------
Animations
--------------------*/

@keyframes shake {
  10%,
  90% {
    transform: translate3d(-1px, 0, 0);
  }
  20%,
  80% {
    transform: translate3d(1px, 0, 0);
  }
  30%,
  50%,
  70% {
    transform: translate3d(-3px, 0, 0);
  }
  40%,
  60% {
    transform: translate3d(3px, 0, 0);
  }
}

.page-enter-active,
.page-leave-active {
  transition: opacity 0.25s;
}
.page-enter,
.page-leave-to {
  opacity: 0;
}
</style>
