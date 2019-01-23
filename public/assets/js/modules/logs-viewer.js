Brackets
	.addComponent('logsItem', {
		instanceId: 'logsItem',
		data: {
			toggled: false,
			deleted: false
		},
		methods: {
			showNestedLogs: function (event, filePath) {
				this.toggled = ! this.toggled;

				if ( ! this.log.nestedLogs.length) {
					var
						self = this,
						log = this.log;

					Utils.ajax({
						url: window.getLogsUrl,
						data: {
							directoryPath: filePath
						},
						start: function () {
							document.body.style.cursor='wait';
						},
						complete: function (response) {
							log.nestedLogs = Utils.json.parse(response.data);
							self.log = log;
							document.body.style.cursor='default';
						}
					});
				}
			},
			deleteLogFile: function (event, filePath) {
				var self = this;
				if (confirm('File: "' + filePath + '" will be deleted')) {
					Utils.ajax({
						url: window.deleteLogUrl,
						data: {
							filePath: filePath
						},
						start: function () {
							document.body.style.cursor='wait';
						},
						complete: function (response) {
							self.deleted = true;
							document.body.style.cursor='default';
						}
					});
				}
			}
		},
		afterRender: function () {
			if (this.data.deleted) {
				document.querySelector(this.el).remove();
			}
		},
		template: '#logs-list-item-template'
	})
	.addComponent('logsList', {
		instanceId: 'logsList',
		template: '#logs-list-template'
	})
	.render({
		instanceId: 'logs-viewer',
		el: '#logs-list',
		data: {
			logs: JSON.parse(window.logs)
		},
		template: function () {
			return this.data.logs.length
				? '{{component logsList, logs: logs, toggled: true}}'
				: '<h1>No logs found.</h1>'
		}
	});
