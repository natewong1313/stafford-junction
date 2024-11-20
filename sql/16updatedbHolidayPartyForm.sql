ALTER TABLE dbBrainBuildersHolidayPartyForm
DROP CONSTRAINT FK_brainbuilders_family_id;

ALTER TABLE dbBrainBuildersHolidayPartyForm
DROP COLUMN family_id;

ALTER TABLE dbBrainBuildersHolidayPartyForm
ADD COLUMN child_id INT NOT NULL AFTER id;

ALTER TABLE dbBrainBuildersHolidayPartyForm
ADD CONSTRAINT fk_holidayParty_form_child
FOREIGN KEY(child_id) REFERENCES dbChildren(id);


