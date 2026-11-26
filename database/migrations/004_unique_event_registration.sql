-- Apply once to the existing tru_gaming database.
-- Resolve any pre-existing duplicate pairs before adding this constraint.
ALTER TABLE registrations
    ADD CONSTRAINT uq_registration_member_event UNIQUE (member_id, event_id);
