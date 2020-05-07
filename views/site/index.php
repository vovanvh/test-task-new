<?php
/* @var array $defaultValues */
/* @var string $data */
?><div style="height:150px;"></div>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <form name="submit-task" method="post">
            <div class="form-group">
                <label for="user_name">First name</label>
                <input value="<?= $defaultValues['firstName'] ?>" type="text" class="form-control" name="first_name" id="first_name" placeholder="First name" data-required="1">
            </div>
            <div class="form-group">
                <label for="user_name">Last name</label>
                <input value="<?= $defaultValues['lastName'] ?>" type="text" class="form-control" name="last_name" id="last_name" placeholder="Last name" data-required="1">
            </div>
            <div class="form-group">
                <label for="user_name">Date of birth</label>
                <input value="<?= $defaultValues['dateOfBirth'] ?>" type="text" class="form-control" name="dob" id="dob" placeholder="Date of birth" data-required="1">
            </div>
            <div class="form-group">
                <label for="user_name">Salary</label>
                <input value="<?= $defaultValues['Salary'] ?>" type="text" class="form-control" name="salary" id="salary" placeholder="Salary" data-required="1">
            </div>
            <div class="radio"> Credit score
                <label>
                    <input type="radio" name="credit_score" value="good" checked> Good <br />
                    <input type="radio" name="credit_score" value="bad"> Bad <br />
                    <input type="radio" name="credit_score" value="zero"> Not set (to check error response)
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="format" value="xml" checked> XML <br />
                    <input type="radio" name="format" value="json"> Json
                </label>
            </div>
            <button type="submit" class="btn btn-success">Send</button>
        </form>
    </div>
    <div class="col-md-1"></div>
</div>
<div><?= htmlspecialchars($data) ?></div>
