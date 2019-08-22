Vue.component('logs-list', {
	props: ['logs'],
	template: '#logs-list-template'
});

Vue.component('log-item', {
	props: ['log'],
	data () {
		return {
			toggled: false,
			deleted: false,
			viewLogUrl: window.viewLogUrl
		}
	},
	methods: {
		showNestedLogs(filePath) {
			this.toggled = ! this.toggled;

			if ( ! this.log.nestedLogs.length) {
				let
					self = this,
					log = self.log;

				Utils.ajax({
					url: window.getLogsUrl,
					data: {
						directoryPath: filePath
					},
					start: function () {
						document.body.style.cursor = 'wait';
					},
					complete: function (response) {
						log.nestedLogs = Utils.json.parse(response.data);
						self.log = log;
						document.body.style.cursor = 'default';
					}
				});
			}
		},
		deleteLogFile(filePath) {
			var self = this;

			if (confirm('File: "' + filePath + '" will be deleted')) {
				Utils.ajax({
					url: window.deleteLogUrl,
					data: {
						filePath: filePath
					},
					start: function () {
						document.body.style.cursor = 'wait';
					},
					complete: function (response) {
						self.deleted = true;
						document.body.style.cursor = 'default';
					}
				});
			}
		}
	},
	template: '#logs-list-item-template'
})


let logs = Utils.json.parse(window.logs);

if (logs.length) {
	new Vue({
		el: '#logs-list',
		data () {
			return {
				logs: logs
			}
		},
		template: '#logs-list-template'
	});

} else {
	new Vue({
		el: '#logs-list',
		template: '<h1 class="container text-center">No logs found.</h1>'
	});
}
