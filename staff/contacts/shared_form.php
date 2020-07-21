                <?php
                use \utility\Utility;
                ?>
                <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name</label>
                    <input placeholder='First Name' type="text" class="form-control" id="firstName" name='contact[name_first]' value="<?php echo Utility::h($entry->name_first); ?>">
                </div>
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input placeholder='Last Name' type="text" class="form-control" id="lastName" name='contact[name_last]' value="<?php echo Utility::h($entry->name_last); ?>">
                </div>
                <div class="col-md-4">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input placeholder='2001-01-31' type="date" class="form-control" id="dob" name='contact[date_of_birth]' value="<?php echo $entry->dateOfBirth(); ?>">
                </div>
                <div class="col-12">
                    <label for="emailAddress" class="form-label">Email Address</label>
                    <input placeholder='user@domain.com' type="email" class="form-control" id="emailAddress" name='contact[email]' value="<?php echo Utility::h($entry->email); ?>">
                </div>
                <div class="col-md-6">
                    <label for="homePhone" class="form-label">Home Phone</label>
                    <input placeholder='111-222-3333' type="text" class="form-control" id="homePhone" name='number[phone_home]' value="<?php echo Utility::h($entry->phone_home); ?>">
                </div>
                <div class="col-md-6">
                    <label for="mobilePhone" class="form-label">Mobile Phone</label>
                    <input placeholder='111-222-3333' type="text" class="form-control" id="mobilePhone" name='number[phone_mobile]' value="<?php echo Utility::h($entry->phone_mobile); ?>">
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="1234 Main St" name='contact[address]' value="<?php echo Utility::h($entry->address); ?>">
                </div>
                <div class="col-md-6">
                    <label for="address2" class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="address2" placeholder="Apartment, unit, studio, or floor" name='contact[address2]' value="<?php echo Utility::h($entry->address2); ?>">
                </div>
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name='contact[city]' value="<?php echo Utility::h($entry->city); ?>">
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">State</label>
                    <select id="state" class="form-select"  name='contact[state]'>
                        <option value="">Select a State</option>
                        <?php
                        # populate the drop down with an array of states - for now
                        $selected = null;
                        foreach ($usaStatesArray as $key => $val) {
                            $selected = null;
                            if ($entry->state == $key) {
                                $selected = 'selected';
                            }
                            echo "<option value='{$key}' $selected>{$val}</option>";
                        }
                        ?>
                        
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="zip" class="form-label">Zip</label>
                    <input placeholder='21331' type="number" class="form-control" id="zip" name='number[zip]' value="<?php echo Utility::h($entry->zip); ?>">
                </div>