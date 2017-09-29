<?php

namespace Authorization\Entity;

/**
 * ORM-модель таблицы public.user
 * 
 * Если название поля класса(например, $surname) совпадает с названием столбца в таблице(surname),
 * тогда параметр name аннотации @Column указывать необязательно.
 * 
 * Например, следующие аннотации тождественны:
 * 
 * @Column(type="string", name="patronymic")
 * @Column(type="string")
 * 
 * Пример сохранения модели в базу: 
 * ```php
 * 	// Создаем объект с нужными данными
 *	$user = new User();
 *	$user->setName		('Брайан');
 *	$user->setSurname	('Керниган');
 *	$user->setPatronymic('Уилсон');
 *	$user->setPost		('Разработчик');
 *	$user->setRank		('Инженер-пограмист');
 *	
 *	// Сохраняем в БД через EntityManager
 *	$entityManager->persist($user);
 *	$entityManager->flush();
 * ```
 * 
 * Пример получения объекта по уникальному ID:
 * ```php
 * $userId = 2;
 * $user = $entityManager->find(User::class, $userId)
 * ```
 * @Entity @Table(name="public.user")
 * 
 * @author SotnikovDS
 */
class User
{
	/** 
	 * Primary key
	 * 
	 * @Id @Column(type="integer") 
	 * @GeneratedValue 
	 */
	protected $id;
	
	/**
	 * Имя
	 * 
	 * @Column(type="string", name="name") 
	 */
	protected $name;
	
	/**
	 * Фамилия
	 *  
	 * @Column(type="string", name="surname")
	 */
	protected $surname;
	
	/**
	 * Отчество
	 *  
	 * @Column(type="string", name="patronymic")
	 */
	protected $patronymic;
	
	/**
	 * Должность
	 *
	 * @Column(type="string")
	 */
	protected $post;
	
	/**
	 * Звание
	 *
	 * @Column(type="string")
	 */
	protected $rank;
	
	
	public function getId()
	{
		return $this->id;
	}
	
	//*******SETTERS*******
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setSurname($surname)
	{
		$this->surname = $surname;
	}
	
	public function setPatronymic($patronymic)
	{
		$this->patronymic = $patronymic;
	}
	
	public function setPost($post)
	{
		$this->post = $post;
	}
	
	public function setRank($rank)
	{
		$this->rank = $rank;
	}
	
	//*******GETTERS*******
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getSurname()
	{
		return $this->surname;
	}
	
	public function getPatronymic()
	{
		return $this->patronymic;
	}
	
	public function getPost()
	{
		return $this->post;
	}
	
	public function getRank()
	{
		return $this->rank;
	}
}