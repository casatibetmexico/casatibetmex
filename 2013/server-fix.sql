UPDATE wp_posts set post_content = replace(post_content, 'http://ct.local/2013', 'http://www2.casatibet.org.mx');
UPDATE wp_posts set guid = replace(guid, 'http://ct.local/2013', 'http://www2.casatibet.org.mx');
UPDATE wp_posts set post_content = replace(post_content, 'http://ct.local', 'http://www2.casatibet.org.mx');
UPDATE wp_posts set guid = replace(guid, 'http://ct.local', 'http://www2.casatibet.org.mx');

UPDATE wp_postmeta set meta_value = replace(meta_value, 'http://ct.local/2013', 'http://www2.casatibet.org.mx');
UPDATE wp_postmeta set meta_value = replace(meta_value, 'http://ct.local', 'http://www2.casatibet.org.mx');

UPDATE wp_options set option_value = replace(option_value, 'http://ct.local/2013', 'http://www2.casatibet.org.mx');
UPDATE wp_options set option_value = replace(option_value, 'http://ct.local', 'http://www2.casatibet.org.mx');
UPDATE wp_options set option_value = replace(option_value, 'ct.local', 'casatibet.org.mx');