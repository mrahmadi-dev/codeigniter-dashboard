<?= $this->extend('admin_layout') ?>

<?= $this->section('content') ?>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                                Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                            ><i class="bx bx-bell me-1"></i> Notifications</a
                            >
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                            ><i class="bx bx-link-alt me-1"></i> Connections</a
                            >
                        </li>
                    </ul>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div style="color: red" id="profile_img_form_errors"></div>
                            <form id="profile_img_form" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="acc" value="updateProfileImage">
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img
                                            src="<?= base_url() ?><?= $userImage ?>"
                                            alt="user-avatar"
                                            class="d-block rounded"
                                            height="100"
                                            width="100"
                                            id="uploadedAvatar"
                                    />
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Choose new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input
                                                    type="file"
                                                    name="img"
                                                    id="upload"
                                                    class="account-file-input"
                                                    hidden
                                                    accept="image/png, image/jpeg"
                                            />
                                        </label>
                                        <button type="submit" class="btn btn-success mb-4">Upload</button>
                                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 100K</p>
                                        <p class="text-muted mb-0">Allowed dimension 1024,768</p>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <hr class="my-0"/>
                        <div class="card-body">
                            <form id="profile_form" method="POST" action="">
                                <?= csrf_field() ?>
                                <p id="profile_form_errors">

                                </p>
                                <input type="hidden" name="acc" value="updateProfile">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input
                                                class="form-control"
                                                type="text"
                                                id="name"
                                                name="name"
                                                value="<?= esc($name) ?>"
                                                autofocus
                                        />
                                    </div>


                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">Phone Number</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">IR (+98)</span>
                                            <input
                                                    type="text"
                                                    id="phoneNumber"
                                                    name="phoneNumber"
                                                    value="<?= esc($phone_number) ?>"
                                                    class="form-control"
                                                    placeholder="09147706011"
                                            />
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                               value="<?= esc($address) ?>" placeholder="Address"/>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="language" class="form-label">Language</label>
                                        <select id="language" class="select2 form-select" name="language">
                                            <option value="">Select Language</option>
                                            <option <?= esc($language) == 'en' ? 'selected' : '' ?> value="en">English
                                            </option>
                                            <option <?= esc($language) == 'fa' ? 'selected' : '' ?> value="fa">Farsi
                                            </option>
                                            <option <?= esc($language) == 'fr' ? 'selected' : '' ?> value="fr">French
                                            </option>
                                            <option <?= esc($language) == 'de' ? 'selected' : '' ?> value="de">German
                                            </option>
                                            <option <?= esc($language) == 'pt' ? 'selected' : '' ?> value="pt">
                                                Portuguese
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select id="currency" class="select2 form-select" name="currency">
                                            <option value="">Select Currency</option>
                                            <option <?= esc($currency) == 'usd' ? 'selected' : '' ?> value="usd">USD
                                            </option>
                                            <option <?= esc($currency) == 'rial' ? 'selected' : '' ?> value="rial">
                                                RIAL
                                            </option>
                                            <option <?= esc($currency) == 'euro' ? 'selected' : '' ?> value="euro">
                                                Euro
                                            </option>
                                            <option <?= esc($currency) == 'pound' ? 'selected' : '' ?> value="pound">
                                                Pound
                                            </option>
                                            <option <?= esc($currency) == 'bitcoin' ? 'selected' : '' ?>value="bitcoin">
                                                Bitcoin
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Account -->
                    </div>
                    <div class="card">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                            <div class="mb-3 col-12 mb-0">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your
                                        account?</h6>
                                    <p class="mb-0">Once you delete your account, there is no going back. Please be
                                        certain.</p>
                                </div>
                            </div>
                            <form id="formAccountDeactivation" action="/admin/profile/deactivateAccount" method="get">
                                <div class="form-check mb-3">
                                    <input
                                            class="form-check-input"
                                            type="checkbox"
                                            required
                                            name="accountActivation"
                                            id="accountActivation"
                                    />
                                    <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                                </div>
                                <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by
                    <a href="https://themeselection.com" target="_blank"
                       class="footer-link fw-bolder">ThemeSelection</a>
                </div>
                <div>
                    <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                    <a
                            href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                            target="_blank"
                            class="footer-link me-4"
                    >Documentation</a
                    >

                    <a
                            href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                            target="_blank"
                            class="footer-link me-4"
                    >Support</a
                    >
                </div>
            </div>
        </footer>
        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

<?= $this->endSection() ?>