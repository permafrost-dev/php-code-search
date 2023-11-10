<?php

$checkInResponse = CheckInResponse::whereCheckInRequestUuid($requestUuid)::whereSessionRegistrationUuid($sessionRegistration->uuid)
    ->firstOrFail();
