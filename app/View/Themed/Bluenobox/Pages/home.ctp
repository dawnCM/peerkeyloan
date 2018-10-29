<div id="cm_masthd_wrap">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="cm_masthd_contentbox" class="clearfix">
					<div class="cm_masthd_contentbox_row">
						<div id="cm_masthd_title">
							Fast, Secure and Easy! <strong><span class="highlight"><?php if($this->Session->read('Application.Mobile') !== true){echo 'Online';}else{echo '<br/>';}?> Approvals in Minutes!</span></strong>
						</div>
					</div>
					<div class="cm_masthd_contentbox_row">
						<div id="cm_masthd_lcol">
							<div id="cm_masthd_lcol_title">
								Loans for <strong><span class="highlight">ALL CREDIT TYPES</span></strong>
							</div>
							<div class="cm_masthd_lcol_loantype">
								<strong><span class="highlight">Short Term</span></strong> Loans up to
							</div>
							<div class="cm_masthd_lcol_loanamt">$1,000</div>
							<div class="cm_masthd_lcol_loantype">
								<strong><span class="highlight">Longer Term</span></strong>
								Loans up to
							</div>
							<div class="cm_masthd_lcol_loanamt">$5,000</div>
						</div>
						<div id="cm_masthd_rcol">
							<form class="form-horizontal" role="form" id="LeadInForm" action="/application" method="post">
								<div class="form-group">
									<label for="CreditRating" class="col-sm-5 control-label">Rate your credit:</label>
									<div class="col-sm-6">
										<select name="CreditRating" class="form-control" id="CreditRating" tabindex="1" data-parsley-required="true">
											<option value="">-Select-</option>
											<option value="excellent" <?php echo ($this->Session->read('Application.CreditRating') == 'excellent') ? 'selected="selected"' : '' ?>>Excellent (760+)</option>
											<option value="good"<?php echo ($this->Session->read('Application.CreditRating') == 'good') ? 'selected="selected"' : '' ?>>Good (700+)</option>
											<option value="fair"<?php echo ($this->Session->read('Application.CreditRating') == 'fair') ? 'selected="selected"' : '' ?>>Fair (640+)</option>
											<option value="poor"<?php echo ($this->Session->read('Application.CreditRating') == 'poor') ? 'selected="selected"' : '' ?>>Poor</option>
											<option value="unsure"<?php echo ($this->Session->read('Application.CreditRating') == 'unsure') ? 'selected="selected"' : '' ?>>Unsure</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="Zip" class="col-sm-5 control-label">Zip Code:</label>
									<div class="col-sm-6">
										<input name="Zip" type="number" class="form-control" id="Zip" tabindex="2" size="15" maxlength="5" value="<?php echo $this->Session->read('Application.Zip'); ?>" placeholder="Zip Code" 
										data-parsley-required="true" 
										data-parsley-pattern="/^[0-9]{5}?$/"/>
									</div>
								</div>
								<div class="form-group">
									<label for="Military" class="col-sm-5 control-label">Are you active military?</label>
									<div class="col-sm-6">
										<select name="Military" class="form-control" id="Military" tabindex="3" data-parsley-required="true">
											<option value="true">Yes</option>
											<option value="false" selected>No</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="MonthlyNetIncome" class="col-sm-5 control-label">Monthly Income:</label>
									<div class="col-sm-6">
										<div class="input-group">
											<div id="MonthlyNetIncomeAddon1" class="input-group-addon">$</div>
											<input type="number" class="form-control"	name="MonthlyNetIncome" id="MonthlyNetIncome" value="<?php echo $this->Session->read('Application.MonthlyNetIncome'); ?>" placeholder="Income" maxlength="5" tabindex="4"
											data-parsley-required="true" 
											data-parsley-type="digits" 
											data-parsley-length="[1,5]"/>
											<div id="MonthlyNetIncomeAddon2" class="input-group-addon">.00</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<div class="ckbox ckbox-success">
											<input type="checkbox" value="true" id="Agree" tabindex="5" name="Agree"/> <label
												for="Agree">I am / we are over Eighteen (18) years of age,
												am / are a U.S. resident and am not currently in bankruptcy.
												I/We have read and agree to the <a
												href="https://global.leadstudio.com/terms"
												data-title="Terms and Conditions" data-toggle="lightbox"
												data-gallery="remoteload">Terms and Conditions</a>, <a
												href="https://global.leadstudio.com/privacy?site=Peer%20Key%20Loan"
												data-title="Privacy Policy" data-toggle="lightbox"
												data-gallery="remoteload">Privacy Policy</a> and <a
												href="https://global.leadstudio.com/econsent"
												data-title="E-consent" data-toggle="lightbox"
												data-gallery="remoteload">E-consent</a>.
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8 text-center">
										<a tabindex="6" id="button-app-start" class="btn-lg btn-warning" role="button">NEXT <span class="glyphicon glyphicon-chevron-right"></span></a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="cm_sec02_wrap">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<div class="cm_sec02_icontitle">
					<i class="fa fa-pencil-square-o"></i> Create Your Profile
				</div>
				<div class="cm_sec02_icontext">It's easy to get the cash you need. Simply create your profile, submit, and we'll do the rest.</div>
			</div>
			<div class="col-sm-4">
				<div class="cm_sec02_icontitle">
					<i class="fa fa-thumbs-up"></i> Quick Approvals
				</div>
				<div class="cm_sec02_icontext">Our lenders approve customers online in minutes! Never wait hours or days to see if you're approved for cash.</div>
			</div>
			<div class="col-sm-4">
				<div class="cm_sec02_icontitle">
					<i class="fa fa-money"></i> Get Your Funds
				</div>
				<div class="cm_sec02_icontext">In most cases, you can access your cash the next business day. Don't worry- get your loan today!</div>
			</div>
		</div>
	</div>
</div>
<div id="cm_banner_wrap">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="cm_banner_text">Your request is presented fast, safe and securely to our lender network.</div>
			</div>
		</div>
	</div>
</div>
<div id="cm_slider_wrap">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-dark-head widget-slider">
					<div class="panel-body">
						<div id="carousel-example-generic2" class="carousel slide"
							data-ride="carousel">
							<!-- Wrapper for slides -->
							<div class="carousel-inner">
								<div class="item active">
									<div class="media">
										<div class="media-body">
											<i class="fa fa-users"></i>
											<h4 class="media-heading">More Lenders</h4>
											<p class="media-desc">You get real-time access to hundreds of lenders who are willing to provide you a loan</p>
										</div>
									</div>
									<!-- media -->
								</div>
								<!-- item -->
								<div class="item">
									<div class="media">
										<div class="media-body">
											<i class="fa fa-check-circle"></i>
											<h4 class="media-heading">Less than perfect credit considered</h4>
											<p class="media-desc">Even with no credit or past financial struggles, you may still qualify</p>
										</div>
									</div>
								</div>
								<!-- item -->
								<div class="item">
									<div class="media">
										<div class="media-body">
											<i class="fa fa-desktop"></i>
											<h4 class="media-heading">One Easy Form</h4>
											<p class="media-desc">You can reach more than 100 lenders with just a single profile</p>
										</div>
									</div>
								</div>
								<!-- item -->
							</div>
							<!-- carousel-inner -->
						</div>
						<!-- carousel -->
					</div>
					<!-- panel-body -->
				</div>
				<!-- panel -->
			</div>
		</div>
	</div>
</div>