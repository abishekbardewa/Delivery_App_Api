<script src="<?php echo base_url("assets/js/angular.min.js")?>"></script>
<style>
.ng-invalid{
	border-color: red;
}
.ng-pristine{
	border-color:#DCDCDC !important;
}
</style>
</head>

<div ng-app="">
	<form class="form-horizontal" action="" method="post" name="Form1" id="Form1"/>
		Name: <input type="text" name="fname" ng-model="nm" required><br>
		Age: <input type="number" name="age" ng-model="age" required >
		City: <input type="text" name="cty" ng-model="cty" required><br>
		<button ng-disabled="Form1.$invalid" ng-click="x='true'">Next</button>
	</form>		
	<div  ng-if="x=='true'">
		
		Form is validated
		<form class="form-horizontal" action="" method="post" name="Form2" id="Form2"/>
		Classs: <input type="text" name="fname" ng-model="nm2" required><br>
		Roll: <input type="text" name="age" ng-model="age2" required><br>
		Section: <input type="text" name="cty" ng-model="c2ty" required><br>
		<button ng-disabled="Form2.$invalid" ng-click="x='true'">Next</button>
	</form>
	</div>

</div>