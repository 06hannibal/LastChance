<?php

/**
 * @file
 * Contains \Drupal\person\Entity\Controller\PersonListBuilder.
 */

namespace Drupal\person\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for dictionary_term entity.
 *
 * @ingroup person
 */
class PersonListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;


  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new DictionaryTermListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type term.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;
  }


  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('<ul class="action-links"><li><a class="button button-action button--primary button--small" href="@adminlink">add Person</a></li></ul>.', array(
        '@adminlink' => $this->urlGenerator->generateFromRoute('entity.person_term.term_add'),
      )),
    );
    $build['table'] = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the dictionary_term list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['title'] = $this->t('Title');
    $header['age'] = $this->t('Age');
    $header['body'] = $this->t('Body');
    $header['department'] = $this->t('Department');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\person\Entity\Person */
    $row['id'] = $entity->id();
    $row['title'] = Link::createFromRoute(t($entity->title->value), 'entity.person.canonical', ['person' => $entity->id(),]);
    $row['age'] = $entity->age->value;
    $row['body'] = html_entity_decode(strip_tags($entity->body->value));
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($entity->get('department')->target_id);
    $row['department'] = $term->getName();
    return $row + parent::buildRow($entity);
  }

}
