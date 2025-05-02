<div class="wedding-rsvp-wrapper">
    <form id="wedding-rsvp-form" class="wedding-rsvp-form">
        <h3 class="title">Your Details</h3>

        <div class="input-group">
            <div class="form-control radio-group">
                <h4>
                    Can you Attend? <span class="required-symbol">*</span>
                </h4>
                <div>
                    <input
                        type="radio"
                        name="attend"
                        id="attend"
                        value="Yes"
                        required
                    />
                    <label for="attend">Yes</label>
                </div>
                <div>
                    <input
                        type="radio"
                        name="attend"
                        id="not_attend"
                        value="No"
                        required
                    />
                    <label for="not_attend">No</label>
                </div>
            </div>
        </div>
        <div class="input-group">
            <div class="form-control">
                <label for="fname"
                    >First Name
                    <span class="required-symbol">*</span></label
                >
                <input id="fname" type="text" name="first_name" required />
            </div>
            <div class="form-control">
                <label for="lname"
                    >Last Name <span class="required-symbol">*</span></label
                >
                <input id="lname" type="text" name="last_name" required />
            </div>
        </div>
        <div class="input-group">
            <div class="form-control">
                <label for="email"
                    >Email <span class="required-symbol">*</span></label
                >
                <input id="email" type="text" name="email" required />
            </div>
            <div class="form-control">
                <label for="phone"
                    >Telephone <span class="required-symbol">*</span></label
                >
                <input
                    id="phone"
                    type="number"
                    name="phone"
                    required
                />
            </div>
        </div>

        <div class="form-control">
            <label for="address">
                Address <span class="required-symbol">*</span></label
            >
            <input
                id="address"
                type="text"
                name="address"
                min="0"
                required
            />
        </div>

        <div class="guests-info-container">
            <h3 class="title">Guests Details</h3>
            <div class="input-group guest-fields">
                <div class="form-control">
                    <label for="guest_fname">Guest First Name</label>
                    <input
                        id="guest_fname"
                        type="text"
                        name="guest_fname"
                    />
                </div>
                <div class="form-control">
                    <label for="guest_lname">Guest Last Name</label>
                    <input
                        id="guest_lname"
                        type="text"
                        name="guest_lname"
                    />
                </div>
            </div>
            <!-- Add Another Guest btn -->
            <button type="button" class="add-new-guest-btn">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.5"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-copy-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path stroke="none" d="M0 0h24v24H0z" /><path d="M7 9.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 16.737a2 2 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" /><path d="M11 14h6" /><path d="M14 11v6" /></svg>
                 <span>Add Another Guest</span>
            </button>
        </div>

        <div class="form-control">
            <label for="allergies"
                >If you have any allergies, please let us know</label
            >
            <textarea id="allergies" name="allergies" rows="4"></textarea>
        </div>

        <div class="form-control">
            <label for="message">Message to the Bride and Groom</label>
            <textarea id="message" name="message" rows="4"></textarea>
        </div>

        <div class="response-message"></div>
        <p>
            <!-- after submit processing class will added to the button and then add sniper svg code -->
            <button type="submit" class="rsvp-submit">
                <span>Submit RSVP</span>
            </button>
        </p>
    </form>
</div>