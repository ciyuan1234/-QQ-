<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/*每个页面公共css */
	input{
		color: #333;
		position: relative;
		box-sizing: border-box;
	}
	text{
		position: relative;
		box-sizing: border-box;
	}
	image{
		vertical-align: middle;
	}
	view{
		color: #333;
		position: relative;
		box-sizing: border-box;
	}
	.clear_button:after
	{
		border: none;
	}
	.clear_button
	{
		border: none;
		background: none;
		padding:0px !important;
		margin:0px !important;
		border-radius: 0px;
		line-height: inherit;
	}
</style>
