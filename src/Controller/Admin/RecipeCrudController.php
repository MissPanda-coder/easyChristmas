<?php
namespace App\Controller\Admin;

use App\Entity\Recipe;
use Psr\Log\LoggerInterface;
use App\Form\RecipestepsType;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use App\Form\RecipeHasIngredientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class RecipeCrudController extends AbstractCrudController
{
    private $requestStack;
    private $logger;
    private $security;

        public function __construct(RequestStack $requestStack, LoggerInterface $logger, Security $security)

    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        if ($entityInstance instanceof Recipe) {
            // Assigner l'utilisateur connecté à la recette
            $user = $this->security->getUser();
            $entityInstance->setUser($user);

            // Assigner la date de création si non définie
            if ($entityInstance->getCreatedAt() === null) {
                $entityInstance->setCreatedAt(new \DateTimeImmutable());
            }
        parent::persistEntity($entityManager, $entityInstance);
        }

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('isActive', 'Active'),
            DateTimeField::new('createdAt', 'Créé le')->setFormat('dd/MM/YYYY HH:mm:ss')->hideOnForm(),
            AssociationField::new('recipecategory', 'Catégorie de la recette')
                ->setCrudController(RecipecategoryCrudController::class)
                ->setFormTypeOptions([
                    'class' => Recipecategory::class,
                    'choice_label' => 'categoryname',
                ]),
            AssociationField::new('recipedifficulty', 'Niveau de difficulté')
                ->setCrudController(RecipedifficultyCrudController::class)
                ->setFormTypeOptions([
                    'class' => Recipedifficulty::class,
                    'choice_label' => 'difficultyname',
                ]),
            TextField::new('title', 'Titre de la recette'),
            TextEditorField::new('description', 'Brève description de la recette'),
            TextField::new('photo', 'Nom du fichier de la photo')->onlyOnIndex(),
            IntegerField::new('duration', 'Temps de préparation de la recette'),
            CollectionField::new('ingredients', 'Ingrédients')
                ->setEntryType(RecipeHasIngredientType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            CollectionField::new('recipestep', 'Étapes de préparation de la recette')
                ->setEntryType(RecipestepsType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            ImageField::new('photo', 'Photo de la recette')
                ->setUploadDir('public/uploads/photos')
                ->setBasePath('uploads/photos')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setFormTypeOptions([
                    'required' => true,
                ]),
        ];
    }
}
