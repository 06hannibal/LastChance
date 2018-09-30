<?php

/**
 * @file
 * Contains \Drupal\person\Entity\Person.
 */

namespace Drupal\person\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the advertiser entity.
 *
 * @ingroup person
 *
 * @ContentEntityType(
 *   id = "person",
 *   label = @Translation("Person"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\person\Entity\Controller\PersonListBuilder",
 *     "form" = {
 *       "add" = "Drupal\person\Form\PersonForm",
 *       "edit" = "Drupal\person\Form\PersonForm",
 *       "delete" = "Drupal\person\Form\PersonDeleteForm",
 *     },
 *     "access" = "Drupal\person\PersonAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "person",
 *   admin_permission = "administer person entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "title" = "title",
 *     "image" = "image",
 *     "age" = "age",
 *     "department" = "department",
 *   },
 *   links = {
 *     "canonical" = "/person/{person}",
 *     "edit-form" = "/person/{person}/edit",
 *     "delete-form" = "/person/{person}/delete",
 *     "collection" = "/person/list"
 *   },
 *   field_ui_base_route = "entity.person_term.term_settings",
 * )
 */

class Person extends ContentEntityBase implements ContentEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    // Default author to current user.
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Person entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Person entity.'))
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The name of the Person entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('Image field'))
      ->setSettings([
        'file_directory' => 'person',
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
      ])
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 1,
      ))
      ->setDisplayOptions('form', array(
        'label' => 'hidden',
        'type' => 'image_image',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['age'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Age'))
      ->setDescription(t('Age field'))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'integer',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'label' => 'hidden',
        'type' => 'integer',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['body'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Body'))
      ->setDescription(t('The body of the block'))
      ->setDisplayOptions('view', [
        'type'   => 'text_textarea',
        'weight' => 3
      ])
      ->setDisplayOptions('form', [
        'type'   => 'text_textarea',
        'weight' => 3
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['department'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings',
        [
          'target_bundles' => [
            'department' => 'department'
          ]])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 4,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '30',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}