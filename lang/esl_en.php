<?php
return array(
	'module_esl' => 'Edward Snowden Land',

    # Home
    'mt_edwardsnowdenland_home' => 'Willkommen',
    'md_edwardsnowdenland_home' => 'Edward Snowden Land is a social experiment for direct democracy, because many governments fail on many topics.
     We examine the laws of different countries and suggest improvements. These are discussed and then voted on. If there is a majority, we create a petition for a topic.',

    # Rules
    'list_edwardsnowdenland_rules' => 'Topics',
    'info_esl_rules' => 'Here are the topics being discussed. To create a new topic; There is a button in the left menu.',
    'topic' => 'Topic',

    # Rule
    'md_esl_rule' => 'Rule proposal details',
    'esl_rule_current' => 'Current state',
    'esl_rule_problem' => 'Rule Problem',

    'esl_rule_government' => 'Planned by government',
    'esl_rule_mistake' => 'Their mistake',
    'esl_rule_suggestion' => 'Our suggestion',
    'esl_rule_goal' => 'Our goal',
    'esl_rule_edited_state' => 'Finished editing?',
    'esl_rule_discuss_state' => 'Discussion open?',
    'esl_rule_vote_state' => 'Voting open?',
    'esl_rule_petition_state'  => 'Petition created?',

    'esl_div_now' => 'Current situation',
    'esl_div_gov' => 'The planned changes by the government',
    'esl_div_we' => 'Our proposed changes',

    # Add rule
    'mt_edwardsnowdenland_ruleadd' => 'Add suggestion',
    'info_esl_create_rule' => 'You should write your suggestion in the countries primary language, or your native language. Please note that you cannot edit your texts anymore, when the topic was put into discussion state. Until then, you can freely edit your rule after creation.',
    'msg_esl_rule_added' => 'Your suggestion has been added.',

    # Edit rule
    'mt_edwardsnowdenland_ruleedit' => 'Edit rule suggestion',
    'esl_info_rule_edit' => 'Please edit your rule suggestion until you are satisfied. Then put the suggestion into discussion state.',
    'btn_start_discussion' => 'Start discussion',
    'btn_start_voting' => 'StartVoting',
    'msg_esl_rule_edited' => 'This suggestion has been edited. If you are satisfied, put it into discussion state.',
    'err_esl_cannot_start_discussion' => 'Cannot start discussion for this suggestion. It probably has been started already.',
    'msg_esl_discussion_started' => 'Your suggestion has been put into discussion state. %s E-Mails have been sent to our users.',
    'err_esl_rule_edit_perm' => 'You are not allowed to edit this suggestion. Only staff and the creator can do this.',

    # Discussion mail
    'mails_esl_disc_started' => '%s Discussion started [%s]',
    'mailb_esl_disc_started' => 'Dear %s,
A new topic is being discussed on %s.
    
Country: %s
Topic: %s
Creator: %s
======================================================
%s
======================================================
If you like, you can join the discussion with the following link:
%s
    
Kind Regards
The %2$s Team',
    'esl_mlink_comment' => 'Comment on it',

    'err_esl_voting_start' => 'The votings cannot be started, as they are running or closed already.',
    'mails_esl_voting_started' => '%s Votings started [%s]',
    'mailb_esl_voting_started' => 'Dear %s
The votings for the following topic on %s has started:

%s

==========================================

Please vote for this entry.

%s

or

%s

Kind Regards,
The %2$s Team',


    # Music
    'mt_edwardsnowdenland_music' => 'Friedensmusik',
    'md_edwardsnowdenland_music' => 'Musik bringt die Menschen zusammen. Hier ist eine Playlist mit Liedern die zum Thema Frieden passen. Vorschläge sind Willkommen.',


    # Petition State
    'esl_petition_state' => 'Petition',
    'tt_eslps_new' => 'There is no petition created for this topic yet.',
    'tt_eslps_created' => 'The petition has be created and is currently being voted on.',
    'tt_eslps_voted' => 'The petition is about being published.',
    'tt_eslps_published' => 'The petition has been published on change.org',
    'tt_eslps_succeeded' => 'The petition has reached more than 50,000 subscribers.',
    'tt_eslps_failed' => 'The petition has failed.',


    # Suggest President
    'mt_esl_suggestpresident' => 'Suggest President',
    'md_esl_suggestpresident' => 'Suggest the next Internet President. A president is elected twice a week.',
    'info_esl_add_president' => 'Here you can suggest the next President of ESL, which has to be a citizen of ESL. You can %s people as well. ',
    'msg_president_suggested' => 'You have suggested %s to become the next President. THX For your vote!',
    'err_already_aspiring' => 'This user is already a candidate to be president of ESL.',

    # Apsirant mail
    'mailt_new_aspirtant' => 'New President Suggestion: %s',
    'mailb_new_aspirtant' => '
    Hello %s,
    
    A new president got suggested by %s:
    
    %s
    
    The message is:
    
    %s
    
    You can like him with the following link:
    
    %s
    
    Kind Regards,
    ESL',
);
