Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'blogger',
      path: '/blogger',
      component: require('./components/Tool'),
    },
  ])
})
