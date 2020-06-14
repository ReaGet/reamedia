window.app = new Vue({
	el: '.container',

	data: {

		roomName: '',

		signin: {
			username: '',
			password: ''
		},

		signup: {
			username: '',
			password1: '',
			password2: ''
		},

		enter: {
			roomNumber: '',
			username: '',
			password: ''
		},

		edit: {
			roomNumber: ''
		}		
	},

	created: function() {
		if (localStorage.getItem('auth')) {
			var data = JSON.parse(localStorage.getItem('auth'));

			this.enter.roomNumber = data.roomNumber;
			this.enter.username = data.userName;
			this.signin.username = data.userName;
		}
	},

	methods: {
		createRoom: function(userId) {
			$.ajax({
				url: '../php/createRoom.php',
				method: 'post',
				data: { 
					roomname: app.roomName,
					userId: userId
				},
				success: function(response) {
					var res = JSON.parse(response);

					app.roomNumber = res['number'];
					app.roomName = '';

					$('#createModal').modal('toggle');


					$('.card-columns').append('<div class="card">' +
					    '<div class="card-body">' +
					      	'<h5 class="card-title">' + res['roomname'] + ' - <small>' + res['number'] + '</small></h5>' +
					      	'<p class="card-text"><small class="text-muted">Фотографий: 0</small></p>' +
					      	'<p class="card-text"><small class="text-muted room-status">Статус: активен</small></p>' +
					    '</div>' +
					    '<div class="card-footer">' +
							'<a href="?room=' + res['number'] + '" class="card-link"><small class="text-muted">Открыть</small></a>' +
							'<small v-on:click="edit(' + res['number'] + ')" class="card-link btn-edit">Редактировать</small>' +
						'</div>' +
					 '</div>');

					// $('.table tbody').append('<tr>' +
					//      	'<th scope="row"><input type="checkbox" name=""></th>' +
					// 	    '<td>' + res['roomname'] + '</td>' +
					// 	    '<td>' + res['number'] + '</td>' +
					// 	    '<td>0</td>' +
					// 	    '<td>' +
					// 	    	'<a href="?room=' + res['number'] + '" class="btn btn-outline-warning">Открыть</a>' +
					// 	    	'<div v-on:click="" class="btn btn-outline-info">Редактировать</div>' +
					// 	    '</td>' +
					//     '</tr>');

				} 
			});
		},

		enterRoom: function(e) {
			e.preventDefault();

			$.ajax({
				url: '../php/enterRoom.php',
				method: 'post',
				data: { 
					roomNumber: app.enter.roomNumber,
					username: app.enter.username,
					password: app.enter.password
				},
				success: function(response) {
					var res = JSON.parse(response);

					if (res['status'] == 'ok') {
						localStorage.setItem('auth', JSON.stringify({
							roomNumber: app.enter.roomNumber,
							userName: app.enter.username
						}));


						window.location.href = "/";
						// app.enter.done = true;


						// app.timerId = window.setInterval(function() {
						// 	app.loadImages();
						// }, 3000);
					}

				} 
			});
		},

		uploadData: function(e) {
			e.preventDefault();

			if (!app.enter.roomNumber) {
				var query = window.location.search;
				var urlParams = new URLSearchParams(query);
				app.enter.roomNumber = urlParams.get('room');
			}

			var formData = new FormData($('#uploadImage')[0]);

			formData.append('number', app.enter.roomNumber);
			formData.append('user', app.enter.username);

			$.ajax({
				url: '../php/uploadData.php',
				method: 'post',
				contentType: false,
			    processData: false,
			    data: formData,
			    dataType: 'text',
			    beforeSend: function() {
			    },
			    xhr: function() {
			    	$('#uploadProgress').css('display', 'block');
			    	var xhr = $.ajaxSettings.xhr();
			    	xhr.upload.addEventListener('progress', function(evt) {
			          	if(evt.lengthComputable) {
			            	var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
			            	$('#uploadProgress').width(percentComplete+'%');
			          	}
			        }, false);
			        return xhr;
			    },
			    success: function(response) {
			    	// alert('Фотографии загружены!');
			    	app.loadImages(app.enter.roomNumber);
			    	$('.btn-hide').removeClass('btn-hide');
			    	$('#uploadProgress').width(0+'%');
			    	$('#uploadProgress').css('display', 'none');
			    	// if (response) {
			    	// 	response = JSON.parse(response);
			    	// console.log(response);
			    	// }
			    },
			    error: function(response) {
			    	// console.log('error', response);
			    }
			});
		},

		editRoom: function(roomNumber) {

			$.ajax({
				url: '../php/editRoom.php',
				type: 'post',
				data: {
					roomNumber: roomNumber
				},
				success: function(response) {
					response = JSON.parse(response);
					$('#editModal').modal('toggle');
					$('#editModal').find('.modal-title').html('Номер: ' + response['number']);
					$('#editModal').find('#room-name').val(response['name']);
					$('#editModal').find('#room-password').val(response['password']);
					if (response['status'] == 1)
						$('#editModal').find('#room-status').prop('checked', true);
					else
						$('#editModal').find('#room-status').prop('checked', false);
				}
			});

		},

		saveRoom: function() {
			$.ajax({
				url: '../php/saveRoom.php',
				type: 'post',
				data: {
					roomNumber: this.edit.roomNumber,
					roomName: $('#editModal').find('#room-name').val(),
					roomStatus: $('#room-status').is(':checked') ? 1 : 0,
					roomPassword: $('#editModal').find('#room-password').val()
				},
				success: function(response) {
					response = JSON.parse(response);

					if (response['status'] == 'ok')	{
						$('#room-' + app.edit.roomNumber).find('.room-name').html($('#editModal').find('#room-name').val());
						if ($('#room-status').is(':checked'))
							$('#room-' + app.edit.roomNumber).find('.room-status').html('Статус: активен');
						else
							$('#room-' + app.edit.roomNumber).find('.room-status').html('Статус: неактивен');

						$('#editModal').modal('toggle');
					}
				}
			});
		},

		deleteRoom: function() {
			// var accept = confirm("Вы уверены?");

			if (!confirm("Вы уверены?"))
				return;

			$.ajax({
				url: '../php/deleteRoom.php',
				type: 'post',
				data: {
					roomNumber: this.edit.roomNumber
				},
				success: function(response) {
					response = JSON.parse(response);

					if (response['status'] == 'ok')	{
						$('#room-' + app.edit.roomNumber).remove();
					}
				}
			});
		},

		loadImages: function(roomNumber) {
			this.totalImages = $('.gallery img').length;
			$.ajax({
				url: '../php/loadImages.php',
				type: 'post',
				data: {
					roomNumber: roomNumber,
					count: this.totalImages
				},
				success: function(response) {
					response = JSON.parse(response);

					if (response == 'zero') {
						// alert("Новых Фотографий нет");
						return;
					}

					var images = response;

					app.totalImages += images.length;

					$.each(images, function(){
					    
					    $('<div class="gallery_product"><a data-lsb-group="group1" class="lsb-preview" href="' + this + '"><img src="' + this + '" class="single-image" /></a></div>').appendTo('.gallery'); 
						
					});
					$.fn.lightspeedBox();
				}
			});
		},

		logout: function(e) {
			e.preventDefault();
			$.ajax({

				url: '../php/logout.php',
				type: 'post',

				success: function(res) {
					localStorage.removeItem('auth');
					window.location.href = "/";
				}

			});
		},

		register: function(e) {
			e.preventDefault();

			$.ajax({

				url: '../php/register.php',
				type: 'post',
				data: app.signup,

				success: function(res) {
					localStorage.setItem('auth', JSON.stringify({
						userName: app.signup.username
					}));
					window.location.href = "/";
				}

			})
		},

		login: function(e) {
			e.preventDefault();

			$.ajax({

				url: '../php/login.php',
				type: 'post',
				data: app.signin,

				success: function(res) {
					res = JSON.parse(res);

					if (res == "loggedin") {
						localStorage.setItem('auth', JSON.stringify({
							userName: app.signin.username
						}));
						window.location.href = "/";
					}

				}

			});
		}
	}

});

