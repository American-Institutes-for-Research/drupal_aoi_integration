## Description
The project consists of a custom module that integrates with the OpenAI API to generate summaries using the GPT-4 model.

## Dependencies
[OpenAI Connection](https://www.drupal.org/project/openai_connection)

## Functionality
The module performs the following tasks:

- Checks for the existence of the body field.
- If the body field is found, it adds an "OpenAI Summary" button.
- Clicking the button triggers the generation of a summary using the OpenAI's GPT-4 model.

## Post-Installation
To use the module, the OpenAI API Key needs to be configured. This can be done on the settings page located at `/admin/config/system/openai-connection`.

## Notes
Please note that further development and improvements are required to enhance the functionality and stability of the module.