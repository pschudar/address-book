                <?php
                use \utility\Utility;
                ?>
<div class="w3-row-padding w3-margin-bottom-custom">
                <div class="w3-third">
                    <label for="firstName" class="w3-label">First Name</label>
                    <input placeholder='First Name' type="text" class="w3-input w3-border w3-theme-l5" id="firstName" name='contact[name_first]' value="<?php echo Utility::h($entry->name_first); ?>">
                </div>
                <div class="w3-third">
                    <label for="lastName" class="w3-label">Last Name</label>
                    <input placeholder='Last Name' type="text" class="w3-input w3-border w3-theme-l5" id="lastName" name='contact[name_last]' value="<?php echo Utility::h($entry->name_last); ?>">
                </div>
                <div class="w3-third">
                    <label for="dob" class="w3-label">Date of Birth</label>
                    <input placeholder='2001-01-31' type="date" class="w3-input w3-border w3-theme-l5" id="dob" name='contact[date_of_birth]' value="<?php echo Utility::h($entry->date_of_birth); ?>">
                </div>
</div>
<div class="w3-row-padding w3-margin-bottom-custom">
                <div class="w3-col">
                    <label for="emailAddress" class="w3-label">Email Address</label>
                    <input placeholder='user@domain.com' type="email" class="w3-input w3-border w3-theme-l5" id="emailAddress" name='contact[email]' value="<?php echo Utility::h($entry->email); ?>">
                </div>
</div>
<div class="w3-row-padding w3-margin-bottom-custom">
                <div class="w3-half">
                    <label for="homePhone" class="w3-label">Home Phone</label>
                    <input placeholder='111-222-3333' type="text" class="w3-input w3-border w3-theme-l5" id="homePhone" name='number[phone_home]' value="<?php echo Utility::h($entry->phone_home); ?>">
                </div>
                <div class="w3-half">
                    <label for="mobilePhone" class="w3-label">Mobile Phone</label>
                    <input placeholder='111-222-3333' type="text" class="w3-input w3-border w3-theme-l5" id="mobilePhone" name='number[phone_mobile]' value="<?php echo Utility::h($entry->phone_mobile); ?>">
                </div>
</div>
<div class="w3-row-padding w3-margin-bottom-custom">
                <div class="w3-half">
                    <label for="address" class="w3-label">Address</label>
                    <input type="text" class="w3-input w3-border w3-theme-l5" id="address" placeholder="1234 Main St" name='contact[address]' value="<?php echo Utility::h($entry->address); ?>">
                </div>
                <div class="w3-half">
                    <label for="address2" class="w3-label">Address 2</label>
                    <input type="text" class="w3-input w3-border w3-theme-l5" id="address2" placeholder="Apartment, unit, studio, or floor" name='contact[address2]' value="<?php echo Utility::h($entry->address2); ?>">
                </div>
</div>
<div class="w3-row-padding w3-margin-bottom-custom">
                <div class="w3-third">
                    <label for="city" class="w3-label">City</label>
                    <input type="text" class="w3-input w3-border w3-theme-l5" id="city" name='contact[city]' value="<?php echo Utility::h($entry->city); ?>">
                </div>
                <div class="w3-third">
                    <label for="inputState" class="w3-label">State</label>
                    <select id="state" class="w3-select w3-border w3-theme-l5"  name='contact[state]'>
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
                <div class="w3-third">
                    <label for="zip" class="w3-label">Zip</label>
                    <input placeholder='21331' type="text" class="w3-input w3-border w3-theme-l5" id="zip" name='number[zip]' value="<?php echo Utility::h($entry->zip); ?>">
                </div>
</div>