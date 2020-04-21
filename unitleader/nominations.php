                <div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title">Adult Nominations</h3>
                    <form action="add-scouts.php" method="post">
                      <input type="hidden" id="unitId" name="unitId" value="<?php echo $getUnitElections['id']; ?>">
                      <input type="hidden" id="accessKey" name="accessKey" value="<?php echo $accessKey; ?>">
                      <div id="eligible-scouts">
                        <?php $counternominations = 0;
                        $nominationsQuery = $conn->prepare("SELECT * from nominations where unitId = ?");
                        $nominationsQuery->bind_param("s", $getUnitElections['id']);
                        $nominationsQuery->execute();
                        $nominationsQ = $nominationsQuery->get_result();
                        if ($nominstionsQ->num_rows > 0) {
                          while ($nominations = $nominationsQ->fetch_assoc()) {
                            if ($counternominations > 0) { ?>
                              <hr></hr>
                            <?php } ?>
                            <input type="hidden" name="nominationsId[<?php echo $counternominations; ?>]" value="<?php echo $nominations['id']; ?>">
                            <div class="form-row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="firstName[<?php echo $counternominations; ?>]" class="required">First Name</label>
                                  <input type="text" id="firstName[<?php echo $counternominations; ?>]" name="firstName[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['firstName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="lastName[<?php echo $counternominations; ?>]" class="required">Last Name</label>
                                  <input type="text" id="lastName[<?php echo $counternominations; ?>]" name="lastName[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['lastName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dob[<?php echo $counternominations; ?>]" class="required">Birthday</label>
                                  <input type="date" id="dob[<?php echo $counternominations; ?>]" name="dob[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['dob']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="bsa_id[<?php echo $counternominations; ?>]" class="required">BSA ID</label>
                                  <input type="text" id="bsa_id[<?php echo $counternominations; ?>]" name="bsa_id[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['bsa_id']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="YearsinScouting[<?php echo $counternominations; ?>]" class="required">Years in Scouting as an Adult</label>
                                  <input type="text" id="YearsinScouting[<?php echo $counternominations; ?>]" name="YearsinScouting[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['YearsinScouting']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="position[<?php echo $counternominations; ?>]" class="required">Position in the Unit</label>
                                  <input type="text" id="position[<?php echo $counternominations; ?>]" name="position[<?php echo $counternominations; ?>]" class="form-control" value="<?php echo $nominations['position']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Address</label>
                                  <input id="address_line1[<?php echo $counternominations; ?>]" name="address_line1[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="Address" value="<?php echo $nominations['address_line1']; ?>" >
                                </div>
                                <div class="form-group">
                                  <input id="address_line2[<?php echo $counternominations; ?>]" name="address_line2[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="Address Line 2 (optional)" value="<?php echo $nominations['address_line2']; ?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>City, State, Zip</label>
                                  <input id="city[<?php echo $counternominations; ?>]" name="city[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="City" value="<?php echo $nominations['city']; ?>" >
                                </div>
                                <div class="form-row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <input id="state[<?php echo $counternominations; ?>]" name="state[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="State" value="<?php echo $nominations['state']; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <input id="zip[<?php echo $counternominations; ?>]" name="zip[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="Zip" value="<?php echo $nominations['zip']; ?>" >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label class="required">Contact Information</label>
                                  <input id="email[<?php echo $counternominations; ?>]" name="email[<?php echo $counternominations; ?>]" type="email" class="form-control" placeholder="Email" value="<?php echo $nominations['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <input id="phone[<?php echo $counternominations; ?>]" name="phone[<?php echo $counternominations; ?>]" type="text" class="form-control" placeholder="Phone" value="<?php echo $nominations['phone']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <?php
                            $counternominations++;
                          }
                        } else {
                          while ($counternominations < 2) {
                            if ($counternominations > 0) { ?>
                              <hr></hr>
                            <?php } ?>
                            <input type="hidden" name="nominationsId[<?php echo $counterEligibleScouts; ?>]" value="new">
                            <div class="form-row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="firstName[<?php echo $counterEligibleScouts; ?>]" class="required">First Name</label>
                                  <input type="text" id="firstName[<?php echo $counterEligibleScouts; ?>]" name="firstName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['firstName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="lastName[<?php echo $counterEligibleScouts; ?>]" class="required">Last Name</label>
                                  <input type="text" id="lastName[<?php echo $counterEligibleScouts; ?>]" name="lastName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['lastName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dob[<?php echo $counterEligibleScouts; ?>]" class="required">Birthday</label>
                                  <input type="date" id="dob[<?php echo $counterEligibleScouts; ?>]" name="dob[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['dob']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="required">BSA ID</label>
                                  <input type="text" id="bsa_id[<?php echo $counterEligibleScouts; ?>]" name="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['bsa_id']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Address</label>
                                  <input id="address_line1[<?php echo $counterEligibleScouts; ?>]" name="address_line1[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address" value="<?php echo $eligibleScout['address_line1']; ?>" >
                                </div>
                                <div class="form-group">
                                  <input id="address_line2[<?php echo $counterEligibleScouts; ?>]" name="address_line2[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address Line 2 (optional)" value="<?php echo $eligibleScout['address_line2']; ?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>City, State, Zip</label>
                                  <input id="city[<?php echo $counterEligibleScouts; ?>]" name="city[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="City" value="<?php echo $eligibleScout['city']; ?>" >
                                </div>
                                <div class="form-row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <input id="state[<?php echo $counterEligibleScouts; ?>]" name="state[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="State" value="<?php echo $eligibleScout['state']; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <input id="zip[<?php echo $counterEligibleScouts; ?>]" name="zip[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Zip" value="<?php echo $eligibleScout['zip']; ?>" >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label class="required">Contact Information</label>
                                  <input id="email[<?php echo $counterEligibleScouts; ?>]" name="email[<?php echo $counterEligibleScouts; ?>]" type="email" class="form-control" placeholder="Email" value="<?php echo $eligibleScout['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <input id="phone[<?php echo $counterEligibleScouts; ?>]" name="phone[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Phone" value="<?php echo $eligibleScout['phone']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <?php
                            $counterEligibleScouts++;
                          }
                        } ?>
                      </div>
                      <div>
                        <button type="button" class="btn btn-secondary mb-2" onclick="addScout('eligible-scouts')">Add another</button>
                      </div>
					<br>
                      <div>
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Save">
                      </div>
                      <script>
                          var counter = <?php echo $counterEligibleScouts; ?>;

                          function addScout(divName) {
                              var hr = document.createElement('hr');
                              var formRow = document.createElement('div');
                              formRow.innerHTML = "<input type='hidden' name='eligibleScoutId["+ counter +"]' value='new'><div class='form-row'>  <div class='col-md-3'><div class='form-group'><label for='firstName["+ counter +"]' class='required'>First Name</label><input type='text' id='firstName["+ counter +"]' name='firstName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='lastName["+ counter +"]' class='required'>Last Name</label><input type='text' id='lastName["+ counter +"]' name='lastName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'>  <label for='dob["+ counter +"]' class='required'>Birthday</label>  <input type='date' id='dob["+ counter +"]' name='dob["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='bsa_id["+ counter +"]' class='required'>BSA ID</label><input type='text' id='bsa_id["+ counter +"]' name='bsa_id["+ counter +"]' class ='form-control' required></div>  </div></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <label>Address</label>  <input id='address_line1["+ counter +"]' name='address_line1["+ counter +"]' type='text' class='form-control' placeholder='Address' ></div><div class='form-group'>  <input id='address_line2["+ counter +"]' name='address_line2["+ counter +"]' type='text' class='form-control' placeholder='Address Line 2 (optional)'></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label>City, State, Zip</label>  <input id='city["+ counter +"]' name='city["+ counter +"]' type='text' class='form-control' placeholder='City' ></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <input id='state["+ counter +"]' name='state["+ counter +"]' type='text' class='form-control' placeholder='State' ></div>  </div>  <div class='col-md-8'><div class='form-group'>  <input id='zip["+ counter +"]' name='zip["+ counter +"]' type='text' class='form-control' placeholder='Zip' ></div>  </div></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label class='required'>Contact Information</label>  <input id='email["+ counter +"]' name='email["+ counter +"]' type='email' class='form-control' placeholder='Email' required></div><div class='form-group'>  <input id='phone["+ counter +"]' name='phone["+ counter +"]' type='text' class='form-control' placeholder='Phone' required></div></div></div>";
                              document.getElementById(divName).appendChild(hr);
                              document.getElementById(divName).appendChild(formRow);
                              counter++;
                          }

                      </script>
                    </form>
                  </div>
                </div>