<?php
namespace App\Controller\Admin;

use App\Entity\Recipe;
use Psr\Log\LoggerInterface;
use App\Form\RecipestepsType;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use App\Repository\UnitRepository;
use App\Form\RecipeHasIngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeCrudController extends AbstractCrudController
{
    private $requestStack;
    private $logger;
    private $security;

        public function __construct(RequestStack $requestStack, LoggerInterface $logger, Security $security, IngredientRepository $ingredientRepository, UnitRepository $unitRepository)

    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->security = $security;
        $this->ingredientRepository = $ingredientRepository;
        $this->unitRepository = $unitRepository;
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

            // Nettoyer la description pour enlever les balises HTML
            $description = strip_tags($entityInstance->getDescription());
            $entityInstance->setDescription($description);

            parent::persistEntity($entityManager, $entityInstance);
        }

    }

    public function configureFields(string $pageName): iterable
    {

        $ingredients = $this->ingredientRepository->ordered();
        $units = $this->unitRepository->orderedUnits();

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
            TextareaField::new('description', 'Brève description de la recette'),
            TextField::new('photo', 'Nom du fichier de la photo')->onlyOnIndex(),
            TimeField::new('duration', 'Temps de préparation de la recette')
                ->renderAsChoice()
                ->setFormat('HH:mm'),
            CollectionField::new('ingredients', 'Ingrédients')
                ->setEntryType(RecipeHasIngredientType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                        'ingredients' => $ingredients,
                        'units' => $units,
                    ],
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
